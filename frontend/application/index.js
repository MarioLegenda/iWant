import Vue from 'vue';
import VueRouter from 'vue-router'
import {Header} from "./js/Header/Header";

import {routes} from "./js/routes";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

new Vue({
    el: '#vue_app',
    router: router,
    template: `<div>
                   <Header></Header>
                   <router-view></router-view>
               </div>`,
    components: {
        Header
    }
});