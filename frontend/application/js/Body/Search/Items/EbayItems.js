import {Item} from "../../Listing/components/Item";

export const EbayItems = {
    template: `
            <div class="EbayItems SearchItems">
                <h1 class="SearchItems_Title">Ebay</h1>
                
                <div v-for="(item, index) in items" :key="index" class="GlobalIdContainer">
                    <item 
                        v-for="(item, index) in item.items" 
                        :key="index" 
                        v-bind:item="item"
                        v-bind:classList="classList"
                        v-bind:show="{taxonomyTitle: false, marketplaceLogo: false}">
                    </item>
                </div>
            </div>
            `,
    props: ['items', 'classList'],
    components: {
        'item': Item
    }
};