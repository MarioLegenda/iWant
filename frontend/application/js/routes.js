import {Homepage} from "./Body/Homepage/Homepage";
import {SingleItem} from "./Body/SingleItem/SingleItem";
import {SearchPage} from "./Body/Search/SearchPage";
import {Guide} from "./Body/Guide";
import {About} from "./Body/About";
import {Features} from "./Body/Features";
import {Progress} from "./Body/Progress";
import {ForYou} from "./Body/ForYou";

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
    {
        path: '/:locale/guide',
        components: {
            'default': Guide,
        },
        name:  'guide',
    },
    {
        path: '/:locale/about',
        components: {
            'default': About,
        },
        name:  'about',
    },
    {
        path: '/:locale/features',
        components: {
            'default': Features,
        },
        name:  'features',
    },
    {
        path: '/:locale/progress',
        components: {
            'default': Progress,
        },
        name:  'guide',
    },
    {
        path: '/:locale/for-you',
        components: {
            'default': ForYou,
        },
        name:  'forYou',
    },
];