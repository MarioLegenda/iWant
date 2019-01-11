import Vuex from "vuex";
import VueRouter from "vue-router";
import vmodal from 'vue-js-modal'
import {routes} from "./routes";
import Vue from "vue";
import {Header} from "./Header/Header";
import {routes as apiRoutes} from "./apiRoutes";
import {RepositoryFactory} from "./services/repositoryFactory";
import {GlobalIdInformation} from "./services/globalIdInformation";
import {LocaleInfo} from "./services/localeInfo";

import {state} from "./store/state";
import {mutations} from "./store/mutations";
import {actions} from "./store/actions";
import {defaultFilters} from "./store/state";
import {getters} from "./store/getters";
import ToggleButton from 'vue-js-toggle-button'
import {Navigation} from "./Navigation/Navigation";
import {GlobalErrorHandler} from "./global/GlobalErrorHandler";
import {SiteLanguageInitialChoiceModal} from "./global/SiteLanguageInitialChoiceModal";
import {addArrayFind} from "./global/polyfill";

export class Init {
    static registerWindowPrototypeMethods() {
        addArrayFind();

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
            if (Array.isArray(val)) {
                return val.length === 0;
            }

            return val === '' || val === null || typeof val === 'undefined';
        };

        window.vueRouterLinkCreate = function(routeName) {
            // method has to be bound to Vue this instance with the bind() method
            // only to be used with native events
            this.$router.push({
                name: routeName,
                params: { locale: this.$localeInfo.locale }
            });
        };

        window.getViewportDimensions = function() {
            const w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
            const h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

            return {
                width: w,
                height: h,
            }
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

        Array.prototype.filterInternal = function(callback) {
            let res = [];
            for (let i = 0; i < this.length; i++) {
                res.push(callback(this[i]));
            }

            return res;
        };

        window.scrollToElement = function(pageElement, increment) {
            var positionX = 0,
                positionY = 0;

            while(pageElement != null){
                positionX += pageElement.offsetLeft;
                positionY += pageElement.offsetTop;
                pageElement = pageElement.offsetParent;

                if (Number.isInteger(increment)) {
                    positionX += increment;
                    positionY += increment;
                }

                window.scrollTo(positionX, positionY);
            }
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
            state: state,
            mutations: mutations,
            actions: actions,
            getters: getters,
        });

        const createVueRouter = () => {
            return new VueRouter({
                mode: 'history',
                routes: routes
            });
        };

        const createErrorHandlers = () => {
            Vue.config.errorHandler = function(err, vm, info) {
                if (window.IWL_ENVIRONMENT === 'dev') {
                    console.error(err, info, err.stack);
                }

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
                if (window.IWL_ENVIRONMENT === 'dev') {
                    console.error(message, source, lineno, colno, error);
                }

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
            const repositoryFactory = new RepositoryFactory(null, (function(store) {
                return function(response) {
                    if (window.IWL_ENVIRONMENT === 'dev') {
                        return response;
                    }

                    if (!isEmpty(response) && response.hasOwnProperty('statusCode')) {
                        if (response.statusCode === 503) {
                            store.commit('httpRequestFailed', response);
                        }

                        if (response.statusCode === 500) {
                            store.commit('httpRequestFailed', response);
                        }
                    }

                    return response;
                }
            }(store)));

            repositoryFactory.AppRepository.asyncGetEbayGlobalIdsInformation((response) => {
                Vue.prototype.$globalIdInformation = new GlobalIdInformation(response.collection.data);
                Vue.prototype.$localeInfo = new LocaleInfo('en', 'en');
                Vue.prototype.$isMobile = false;
                Vue.prototype.$viewportDimensions = getViewportDimensions();
                Vue.prototype.$defaultFilters = defaultFilters;
                Vue.prototype.$repository = repositoryFactory;

                repositoryFactory.AppRepository.getCountries(null,(r) => {
                    Vue.prototype.$countries = r.collection.data;
                });

                if (/Mobi|Android/i.test(navigator.userAgent)) {
                    console.log(`Is mobile with user agent: ${navigator.userAgent}`);

                    Vue.prototype.$isMobile = true;
                }

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

                Vue.use(ToggleButton);
                Vue.use(vmodal);

                new Vue({
                    el: '#vue_app',
                    store,
                    router: createVueRouter(),

                    created() {
                        this._showSiteLanguageChoiceModalIfNecessary();
                        const locale = this._determineLocale();

                        this.$store.dispatch('localeChangedAction', {
                            value: locale,
                            origin: 'Root',
                        });
                    },

                    template: `
                        <transition name="fade">
                            <div class="Global">
                                <Header></Header>
                                                                
                                <navigation></navigation>
                                
                                <div class="ComponentBorderSeparator"></div>
                   
                                <router-view></router-view>
                                
                                <global-error-handler></global-error-handler>
                                
                                <site-language-modal></site-language-modal>
                            </div>
                        </transition>`,
                    methods: {
                        _showSiteLanguageChoiceModalIfNecessary() {
                            const cookieHandler = window.CookieHandler;

                            if (!cookieHandler.readCookie('SiteLanguage')) {
                                setTimeout(() => {
                                    this.$modal.show('site-language-initial-choice-modal');
                                }, 2000);
                            }
                        },

                        _determineLocale() {
                            const cookieHandler = window.CookieHandler;

                            if (cookieHandler.readCookie('SiteLanguage')) {
                                return cookieHandler.readCookie('SiteLanguage');

                                return;
                            }

                            let locale = location.pathname.split('/')[1];

                            if (locale === null || typeof locale === 'undefined' || locale.length === 0) {
                                locale = 'en';
                            }

                            return locale;
                        }
                    },
                    components: {
                        'Header': Header,
                        'site-language-modal': SiteLanguageInitialChoiceModal,
                        'navigation': Navigation,
                        'global-error-handler': GlobalErrorHandler,
                    }
                });
            });
        };

        createErrorHandlers();
        createVueApp();
    }
}