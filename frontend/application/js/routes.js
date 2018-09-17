import {Homepage} from "./Body/Homepage/Homepage";
import {TodaysPicks} from "./Body/Homepage/TodaysPicks";
import {SingleItem} from "./Body/SingleItem/SingleItem";

export const routes = [
    { path: '/', component: Homepage},
    { path: '/', component: TodaysPicks},
    { path: '/item/:marketplace/:name/:itemId', component: SingleItem},
];