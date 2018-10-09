import {Item} from "./Item";

export const ItemList = {
    template: `
        <div class="ItemList">
            <item 
                v-for="(item, index) in items" 
                :key="index" 
                v-bind:item="item"
                v-bind:classList="classList">
            </item>
        </div>
    `,
    props: ['items', 'classList'],
    components: {
        'item': Item,
    }
};