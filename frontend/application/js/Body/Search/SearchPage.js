import {Categories} from "../Homepage/Menu/Categories";
import {Shops} from "../Homepage/Menu/Shops";
import {AdvancedSearch} from "./AdvancedSearch";
import {EbayItems} from "./Items/EbayItems";
import {RepositoryFactory} from "../../services/repositoryFactory";

export const SearchPage = {
    data: function() {
        return {
            ebayHttpInProgress: false,
            ebayItems: [],
        }
    },
    template: `<div id="search_page">
                    <categories-menu></categories-menu>
                    <shops-menu></shops-menu>
                    
                    <advanced-search
                        v-on:get-ebay-items="onGetEbayItems">
                    </advanced-search>
                    
                    <ebay-items
                        classList="Item SearchItemItem">
                    </ebay-items>
               </div>`,
    methods: {
        onGetEbayItems(model) {
            if (this.ebayHttpInProgress === false) {
                const searchRepo = RepositoryFactory.create('search');

                searchRepo.searchEbay(model, (response) => {
                    this.$store.commit('ebaySearchListing', response.collection.data);
                    this.ebayHttpInProgress = false;
                });

                this.ebayHttpInProgress = true;
            }
        }
    },
    components: {
        'categories-menu': Categories,
        'shops-menu': Shops,
        'advanced-search': AdvancedSearch,
        'ebay-items': EbayItems
    }
};