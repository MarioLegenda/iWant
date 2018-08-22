import {StartPage} from "./Body/UniformedSearch/components/StartPage";
import {Promise} from "./Body/UniformedSearch/components/Promise";
import {UniformedSearch} from "./Body/UniformedSearch/components/UniformedSearch";

export const routes = [
    { path: '/', component: StartPage},
    { path: '/promise', component: Promise},
    { path: '/search', component: UniformedSearch},
];