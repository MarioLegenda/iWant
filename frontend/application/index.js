import Vue from 'vue';
import Vuex from 'vuex'
import VueRouter from 'vue-router';
import {Init} from "./js/global";

Init.registerWindowPrototypeMethods();
Init.registerVuePlugins(Vue, [VueRouter, Vuex]);
Init.createVueInstance();