import {StartPage} from "./Body/StartPage";
import {Promise} from "./Body/Promise";
import {UniformedSearch} from "./Body/UniformedSearch";

export const routes = [
    { path: '/', component: StartPage},
    { path: '/promise', component: Promise},
    { path: '/search', component: UniformedSearch},
];