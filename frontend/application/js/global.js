import Vuex from "vuex";
import VueRouter from "vue-router";
import {routes} from "./routes";
import Vue from "vue";
import {Header} from "./Header/Header";
import {Menu} from "./Menu/Menu";
import {routes as apiRoutes} from "./apiRoutes";

export const EBAY = 'Ebay';
export const ETSY = 'Etsy';

class GlobalEventHandler {
    handleCategoriesMenu(classNames) {
        let closeCategoriesMenu = true;
        let closeShopsMenu = true;

        for (let className of classNames) {
            if (new RegExp('Categories').test(className)) {
                closeCategoriesMenu = false;

                break;
            }

            if (new RegExp('Shops').test(className)) {
                closeShopsMenu = false;

                break;
            }
        }

        if (closeCategoriesMenu) {
            this.$store.commit('showCategories', false);
        }

        if (closeShopsMenu) {
            this.$store.commit('showShops', false);
        }
    }
}

export class Init {
    static registerWindowPrototypeMethods() {
        ['Arguments', 'Function', 'String', 'Number', 'Date', 'RegExp'].forEach(
            function(name) {
                window['is' + name] = function(obj) {
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
        const store = new Vuex.Store({
            state: {
                todaysProductsListing: {},
                foundSearchProducts: {
                    ebay: false,
                    etsy: false,
                },
                singleItem: null,
                showCategories: false,
                showShops: false,
                ebaySearchListing: {
                    listing: [],
                },
                etsySearchListing: {
                    listing: [],
                },
                searchTerm: null,
                searchLoading: {
                    searchProgress: false,
                    ebay: false,
                    etsy: false,
                },
            },
            mutations: {
                todaysProductsListing(state, value) {
                    this.state.todaysProductsListing = value;
                },
                singleItem(state, value) {
                    this.state.singleItem = value;
                },
                showCategories(state, value) {
                    this.state.showCategories = value;
                },
                showShops(state, value) {
                    this.state.showShops = value;
                },
                ebaySearchListing(state, value) {
                    this.state.ebaySearchListing = value;
                },
                etsySearchListing(state, value) {
                    this.state.etsySearchListing = value;
                },
                searchTerm(state, value) {
                    this.state.searchTerm = value;
                },
                searchLoading(state, value) {
                    this.state.searchLoading = Object.assign({}, this.state.searchLoading, value);
                },
                foundSearchProducts(state, value) {
                    this.state.foundSearchProducts = Object.assign({}, this.state.foundSearchProducts, value);
                },
            }
        });

        const router = new VueRouter({
            mode: 'history',
            routes: routes
        });

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

        new Vue({
            el: '#vue_app',
            store,
            router: router,
            template: `<div v-on:click="globalEventResolver($event)">
                   <Header></Header>
                   
                   <router-view></router-view>
               </div>`,
            methods: {
                globalEventResolver($event) {
                    const globalEventHandler = new GlobalEventHandler();
                    let classNames = [];

                    for (let elem of $event.path) {
                        classNames.push(elem.className);
                    }

                    globalEventHandler.handleCategoriesMenu.call(this, classNames);
                }
            },
            components: {
                Header
            }
        });
    }
}