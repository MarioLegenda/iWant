import Vuex from "vuex";
import VueRouter from "vue-router";
import {routes} from "./routes";
import Vue from "vue";
import {Header} from "./Header/Header";
import {routes as apiRoutes} from "./apiRoutes";
import {RepositoryFactory} from "./services/repositoryFactory";
import {GlobalIdInformation} from "./services/globalIdInformation";
import {SiteLanguageChoice} from "./Header/SiteLanguageChoice";
import {LocaleInfo} from "./services/localeInfo";
import {translationsMap} from "./translationMap";

export const EBAY = 'Ebay';
export const ETSY = 'Etsy';

export const marketplacesList = {
    ebay: EBAY,
    etsy: ETSY,
};

export class Init {
    static registerWindowPrototypeMethods() {
        ['Arguments', 'Function', 'String', 'Number', 'Date', 'RegExp', 'Boolean'].forEach(
            function(name) {
                window['is' + name] = function(obj) {
                    if (name === 'Object') {
                        return typeof obj === 'object';
                    }

                    return toString.call(obj) === '[object ' + name + ']';
                }
            });

        window.parseInteger = function(obj) {
            if (/^(\-|\+)?([0-9]+|Infinity)$/.test(obj)) {
                return parseInt(obj);
            }

            return NaN;
        };

        window.isEmpty = function(val) {
            return val === '' || val === null || typeof val === 'undefined';
        };

        window.scrollToBoundingElement = function(el) {
            const rect = el.getBoundingClientRect();

            setTimeout(function() {
                window.scrollTo({
                    top: rect.bottom,
                    behavior: 'smooth',
                });
            }, 1000);
        };

        window.getElementGeometry = function getOffset( el ) {
            let _x = 0, _y = 0, _w = 0, _h = 0, temp = el;

            while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
                _x += el.offsetLeft - el.scrollLeft;
                _y += el.offsetTop - el.scrollTop;
                el = el.offsetParent;
            }

            return {
                offsetTop: _y,
                offsetLeft: _x,
                width: temp.offsetWidth,
                height: temp.offsetHeight,
                scrollLeft: el.scrollLeft,
                scrollTop: el.scrollTop,
            };
        };

        window.isObjectEmpty = function(obj) {
            for(let prop in obj) {
                if(obj.hasOwnProperty(prop))
                    return false;
            }

            return JSON.stringify(obj) === JSON.stringify({});
        };

        (function() {
            const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

            const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

            Date.prototype.getMonthName = function() {
                return months[ this.getMonth() ];
            };
            Date.prototype.getDayName = function() {
                return days[ this.getDay() ];
            };
        }) ();
    }

    static registerVuePlugins(Vue, plugins) {
        plugins.map(plugin => Vue.use(plugin));
    }

    static createVueInstance() {
        const store =  new Vuex.Store({
            state: {
                ebaySearchListingLoading: false,
                ebaySearchListing: null,
                searchTerm: null,
                searchInitialiseEvent: {
                    searchUrl: null,
                    model: null,
                    initialised: false,
                    finished: false,
                },
                filtersEvent: {
                    lowestPrice: false,
                    highestPrice: false,
                    highQuality: false,
                    shippingCountries: [],
                    marketplaces: [],
                    taxonomies: [],
                    globalIds: [],
                },
                preparedEbayRequestEvent: null,
                preparedEbayRequestEvents: [],
                preparedSearchInformation: {},
                translationsMap: {}
            },
            mutations: {
                searchTerm(state, value) {
                    this.state.searchTerm = value;
                },

                ebaySearchListing(state, value) {
                    this.state.ebaySearchListing = value;
                },

                ebaySearchListingLoading(state, value) {
                    this.state.ebaySearchListingLoading = value;
                },

                foundSearchProducts(state, value) {
                    this.state.foundSearchProducts = Object.assign({}, this.state.foundSearchProducts, value);
                },

                searchInitialiseEvent(state, value) {
                    this.state.searchInitialiseEvent = Object.assign({}, this.state.searchInitialiseEvent, value);
                },

                filtersEvent(state, value) {
                    this.state.filtersEvent = Object.assign({}, this.state.filtersEvent, value);
                },

                preparedEbayRequestEvent(state, value) {
                    this.state.preparedEbayRequestEvent = value;
                },

                preparedEbayRequestEvents(state, value) {
                    this.state.preparedEbayRequestEvents = value;
                },

                preparedSearchInformation(state, value) {
                    this.state.preparedSearchInformation = value;
                },
                translationsMap(state, value) {
                    this.state.translationsMap = value;
                }
            },
            actions: {
                localeChanged(context, locale) {
                    Vue.prototype.$localeInfo.locale = locale.value;

                    context.commit('translationsMap', translationsMap[locale.value]);
                }
            },
        });

        const createVueRouter = () => {
            return new VueRouter({
                mode: 'history',
                routes: routes
            });
        };

        const createErrorHandlers = () => {
            Vue.config.errorHandler = function(err, vm, info) {
                console.error(err, info, err.stack);

                fetch(apiRoutes.app_post_activity_message, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        activityMessage: {
                            message: `${err.message} ; ${err.stack}`,
                            additionalData: {
                                info: info
                            },
                        },
                    })
                });
            };

            window.onerror = function(message, source, lineno, colno, error) {
                console.error(message, source, lineno, colno, error);

                fetch(apiRoutes.app_post_activity_message, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        activityMessage: {
                            message: message,
                            additionalData: {
                                'source': source,
                                'lineNumber': lineno,
                                'colNumber': colno
                            },
                        },
                    })
                });
            };
        };

        const createVueApp = () => {
            const appRepo = RepositoryFactory.create('app');

            appRepo.asyncGetEbayGlobalIdsInformation((response) => {
                Vue.prototype.$globalIdInformation = new GlobalIdInformation(response.collection.data);
                Vue.prototype.$localeInfo = new LocaleInfo('en', 'en');

                Vue.filter('userFriendlyDate', function(date) {
                    const dateTime = new Date(date);

                    const options = {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        timeZone: 'UTC',
                        timeZoneName: 'short',
                    };

                    return `${dateTime.toLocaleDateString('en-US', options)}`;
                });

                new Vue({
                    el: '#vue_app',
                    store,
                    router: createVueRouter(),
                    created() {
                        let locale = location.pathname.split('/')[1];

                        if (locale === null || typeof locale === 'undefined' || locale.length === 0) {
                            locale = 'en';
                        }

                        this.$store.dispatch('localeChanged', {
                            value: locale,
                            origin: 'Root'
                        });
                    },
                    template: `
                        <transition name="fade">
                            <div class="Global">
                                <Header></Header>
                   
                                <site-language-choice></site-language-choice>

                                <router-view></router-view>
                   
                            </div>
                        </transition>`,
                    components: {
                        Header,
                        'site-language-choice': SiteLanguageChoice,
                    }
                });
            });
        };

        createErrorHandlers();
        createVueApp();
    }
}