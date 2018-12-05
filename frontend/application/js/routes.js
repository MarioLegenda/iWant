import {Homepage} from "./Body/Homepage/Homepage";
import {SingleItem} from "./Body/SingleItem/SingleItem";
import {SearchPage} from "./Body/Search/SearchPage";

export const routes = [
    {
        path: '/:locale?',
        components: {
            'default': Homepage,
        },
        name: 'Home'
    },
    {
        path: '/:locale/item/:name/:itemId',
        component: SingleItem,
        name: 'SingleItem',
    },
    {
        path: '/:locale/search',
        components: {
            'default': SearchPage,
        },
        name: 'SearchHome'
    },
];