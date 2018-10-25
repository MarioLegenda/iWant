import {Homepage} from "./Body/Homepage/Homepage";
import {TodaysPicks} from "./Body/Homepage/TodaysPicks";
import {SingleItem} from "./Body/SingleItem/SingleItem";
import {SearchBox} from "./Header/SearchBox";
import {SearchPage} from "./Body/Search/SearchPage";
import {SearchBoxAdvanced} from "./Body/Search/SearchComponent/SearchBoxAdvanced";

export const routes = [
    { path: '/', components: {
        'default': Homepage,
        'search-box': SearchBox,
    }},
    { path: '/', component: TodaysPicks},
    { path: '/item/:marketplace/:name/:itemId', component: SingleItem},
    { path: '/search', components: {
        'default': SearchPage,
        'search-box-advanced': SearchBoxAdvanced,
    }},
    { path: '/search/:term', components: {
        'default': SearchPage,
        'search-box-advanced': SearchBoxAdvanced
    }}
];