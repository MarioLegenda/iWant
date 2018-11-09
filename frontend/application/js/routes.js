import {Homepage} from "./Body/Homepage/Homepage";
import {SingleItem} from "./Body/SingleItem/SingleItem";
import {SearchBox} from "./Header/SearchBox";
import {SearchPage} from "./Body/Search/SearchPage";

export const routes = [
    {
        path: '/',
        components: {
            'default': Homepage,
            'search-box': SearchBox,
        },
        name: 'Home'
    },
    {
        path: '/:locale/item/:marketplace/:name/:itemId',
        component: SingleItem
    },
    {
        path: '/:locale/search',
        components: {
            'default': SearchPage,
        },
        name: 'SearchHome'
    },
    {
        path: '/:locale/search/:term',
        components: {
            'default': SearchPage,
        },
        name: 'SearchTerm'
    }
];