import {Categories} from "../Homepage/Menu/Categories";
import {Shops} from "../Homepage/Menu/Shops";
import {AdvancedSearch} from "./AdvancedSearch";
import {EbayItems} from "./Items/EbayItems";

export const SearchPage = {
    data: function() {
        return {
            ebayItems: [],
        }
    },
    template: `<div id="search_page">
                    <categories-menu></categories-menu>
                    <shops-menu></shops-menu>
                    
                    <advanced-search 
                        v-on:on-ebay-items-found="onEbayItemsFound">
                    </advanced-search>
                    
                    <ebay-items 
                        v-bind:items="ebayItems"
                        classList="Item SearchItemItem">
                    </ebay-items>
               </div>`,
    methods: {
        onEbayItemsFound(items) {
            this.ebayItems = items;
        }
    },
    components: {
        'categories-menu': Categories,
        'shops-menu': Shops,
        'advanced-search': AdvancedSearch,
        'ebay-items': EbayItems
    }
};