import Vuex from "vuex";
import VueRouter from "vue-router";
import {routes} from "./routes";
import Vue from "vue";
import {Header} from "./Header/Header";
import {Menu} from "./Menu/Menu";

export const EBAY = 'Ebay';
export const ETSY = 'Etsy';

class GlobalEventHandler {
    handleCategoriesMenu(classNames) {
        let closeCategoriesMenu = true;
        for (let className of classNames) {
            if (new RegExp('Categories').test(className)) {
                closeCategoriesMenu = false;

                break;
            }
        }

        console.log('closeCategoriesMenu: ', closeCategoriesMenu);
        console.log('store state: ', this.$store.state.showCategories);

        if (closeCategoriesMenu) {
            this.$store.commit('showCategories', false);
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
                singleItem: null,
                showCategories: false,
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
                }
            }
        });

        const router = new VueRouter({
            mode: 'history',
            routes: routes
        });

        new Vue({
            el: '#vue_app',
            store,
            router: router,
            template: `<div v-on:click="globalEventResolver($event)">
                   <Header></Header>
                   <Categories></Categories>
                   
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
                Header,
                Categories: Menu
            }
        });
    }
}