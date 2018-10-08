import {Item} from "../../Listing/components/Item";

export const EbayItems = {
    template: `
            <div class="EbayItems SearchItems">
                <h1 class="SearchItems_Title">Ebay</h1>
                
                
            </div>
            `,
    created() {
        console.log(this.items);
    },
    props: ['items'],
    components: {
        'item': Item
    }
};