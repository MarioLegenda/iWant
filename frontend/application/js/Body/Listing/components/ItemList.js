import {Item} from "./Item";

export const ItemList = {
    template: `
        <div class="ItemList">
            <item></item>
            <item></item>
            <item></item>
            <item></item>
            <item></item>
            <item></item>
            <item></item>
            <item></item>
        </div>
    `,
    components: {
        'item': Item,
    }
};