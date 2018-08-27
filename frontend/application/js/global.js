import Vuex from "vuex";
import VueRouter from "vue-router";
import {routes} from "./routes";
import Vue from "vue";
import {Header} from "./Header/Header";

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
        }
    }

    static registerVuePlugins(Vue, plugins) {
        plugins.map(plugin => Vue.use(plugin));
    }

    static createVueInstance() {
        const store = new Vuex.Store({
            state: {
                uniformedSearchListing: {},
            },
            mutations: {
                uniformedSearchListing (state, value) {
                    this.state.uniformedSearchListing = value;
                },
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
            template: `<div>
                   <Header></Header>
                   <router-view></router-view>
               </div>`,
            components: {
                Header
            }
        });
    }
}