import Vue from 'vue';
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import {Header} from "./js/Header/Header";

import {routes} from "./js/routes";
import {Tools} from "./js/global";

Tools.registerWindowPrototypeMethods();
Tools.registerVuePlugins(Vue, [VueRouter, Vuex]);

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