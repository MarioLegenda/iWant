import Vuex from "vuex";
import VueRouter from "vue-router";
import {routes} from "./routes";
import Vue from "vue";
import {Header} from "./Header/Header";
import {routes as apiRoutes} from "./apiRoutes";
import {RepositoryFactory} from "./services/repositoryFactory";
import {GlobalIdInformation} from "./services/globalIdInformation";
import {SiteLanguageChoice} from "./Header/SiteLanguageChoice";

const translationsMap = {
    en: {
        filterHeader: 'Sort your search results',
        lowestPriceFilter: 'Lowest price',
        highestPriceFilter: 'Highest price',
        highQualityFilter: 'High quality',
    },

    fr: {
        filterHeader: 'Trier vos résultats de recherche',
        lowestPriceFilter: 'Prix ​​le plus bas',
        highestPriceFilter: 'Le prix le plus élevé',
        highQualityFilter: 'Haute qualité',
    },

    es: {
        filterHeader: 'Ordena tus resultados de búsqueda',
        lowestPriceFilter: 'El precio más bajo',
        highestPriceFilter: 'Precio más alto',
        highQualityFilter: 'Alta calidad',
    },

    ga: {
        filterHeader: 'Sórtáil do thorthaí cuardaigh',
        lowestPriceFilter: 'Praghas is ísle',
        highestPriceFilter: 'An praghas is airde',
        highQualityFilter: 'Ardchaighdeáin',
    },

    it: {
        filterHeader: 'Ordina i risultati della tua ricerca',
        lowestPriceFilter: 'Prezzo più basso',
        highestPriceFilter: 'Il prezzo più alto',
        highQualityFilter: 'Alta qualità',
    },

    pl: {
        filterHeader: 'Sortuj swoje wyniki wyszukiwania',
        lowestPriceFilter: 'Najniższa cena',
        highestPriceFilter: 'Wyzsza cena',
        highQualityFilter: 'Wysoka jakość',
    }
};

class SupportedSites {
    constructor(sites) {
        this.sites = sites;
    }

    find(globalId) {
        for (const site of this.sites) {
            if (site.globalId === globalId.toUpperCase()) {
                return site;
            }
        }

        throw new Error(`Global id ${globalId} not found as a supported site`);
    }

    tryFind(globalId) {
        for (const site of this.sites) {
            if (site.globalId === globalId.toUpperCase()) {
                return site;
            }
        }

        return false;
    }

    has(globalId) {
        return this.tryFind(globalId);
    }
}

export const EBAY = 'Ebay';
export const ETSY = 'Etsy';

export const marketplacesList = {
    ebay: EBAY,
    etsy: ETSY,
};

export const SUPPORTED_SITES = new SupportedSites([
    {globalId: 'EBAY-AT', icon: `/images/country_icons/ebay-at.svg`},
    {globalId: 'EBAY-DE', icon: `/images/country_icons/ebay-de.svg`},
    {globalId: 'EBAY-ES', icon: `/images/country_icons/ebay-es.svg`},
    {globalId: 'EBAY-FR', icon: `/images/country_icons/ebay-fr.svg`},
    {globalId: 'EBAY-FRBE', icon: `/images/country_icons/ebay-frbe.svg`},
    {globalId: 'EBAY-GB', icon: `/images/country_icons/ebay-gb.svg`},
    {globalId: 'EBAY-IT', icon: `/images/country_icons/ebay-it.svg`},
    {globalId: 'EBAY-US', icon: `/images/country_icons/ebay-us.svg`},
    {globalId: 'EBAY-IE', icon: `/images/country_icons/ebay-ie.svg`},
    {globalId: 'EBAY-PL', icon: `/images/country_icons/ebay-pl.svg`},
]);

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
                    context.commit('translationsMap', translationsMap[locale]);
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

                new Vue({
                    el: '#vue_app',
                    store,
                    router: createVueRouter(),
                    created() {
                        this.$store.dispatch('localeChanged', 'en');
                    },
                    template: `<div class="Global">
                   <Header></Header>
                   
                   <site-language-choice></site-language-choice>
                   
                   <router-view></router-view>
               </div>`,
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