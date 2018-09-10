import {Item} from "./Item";

export const ItemList = {
    template: `
        <div class="ItemList">
            <item v-for="(item, index) in items" :key="index" v-bind:item="item"></item>
        </div>
    `,
    props: ['items'],
    components: {
        'item': Item,
    }
};