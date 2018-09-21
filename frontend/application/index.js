import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import VTooltip from 'v-tooltip';
import {Init} from "./js/global";

Init.registerWindowPrototypeMethods();
Init.registerVuePlugins(Vue, [VueRouter, Vuex, VTooltip]);
Init.createVueInstance();