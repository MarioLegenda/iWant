import {Item} from "../../Listing/components/Item";

export const EbayItems = {
    template: `
            <div v-if="ebaySearchListing.length > 0" class="EbayItems SearchItems">
                <h1 class="SearchItems_Title">eBay</h1>
                
                <div v-for="(item, index) in ebaySearchListing" :key="index" class="GlobalIdContainer">
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
    computed: {
        ebaySearchListing: function() {
            return this.$store.state.ebaySearchListing;
        }
    },
    props: ['classList'],
    components: {
        'item': Item
    }
};