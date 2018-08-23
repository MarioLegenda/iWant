import Vue from 'vue';
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import {Header} from "./js/Header/Header";

import {routes} from "./js/routes";

['Arguments', 'Function', 'String', 'Number', 'Date', 'RegExp'].forEach(
    function(name) {
        window['is' + name] = function(obj) {
            return toString.call(obj) === '[object ' + name + ']';
        };
});

Vue.use(VueRouter);
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        search: {
            keywords: '',
            filters: []
        }
    },
    mutations: {
        addKeywords (state, value) {
            state.search.keywords = value;
        },
        addFilter (state, value) {
            state.search.filters.push(value);
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
    template: `<div>
                   <Header></Header>
                   <router-view></router-view>
               </div>`,
    components: {
        Header
    }
});