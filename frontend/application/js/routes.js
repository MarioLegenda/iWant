import {SingleItem} from "./Body/SingleItem/SingleItem";
import {SearchPage} from "./Body/Search/SearchPage";
import {Guide} from "./Body/Guide";
import {Features} from "./Body/Features";
import {Progress} from "./Body/Progress";
import {ForYou} from "./Body/ForYou";

export const routes = [
    {
        path: '/:locale?',
        components: {
            'default': SearchPage,
        },
        name: 'Home',
    },
    {
        path: '/:locale/item/:name/:itemId',
        component: SingleItem,
        name: 'SingleItem',
    },
    {
        path: '/:locale/guide',
        components: {
            'default': Guide,
        },
        name:  'Guide',
    },
    {
        path: '/:locale/features',
        components: {
            'default': Features,
        },
        name:  'Features',
    },
    {
        path: '/:locale/progress',
        components: {
            'default': Progress,
        },
        name:  'Progress',
    },
    {
        path: '/:locale/for-you',
        components: {
            'default': ForYou,
        },
        name:  'ForYou',
    },
];