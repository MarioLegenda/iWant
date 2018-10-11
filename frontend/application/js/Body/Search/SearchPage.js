import {Categories} from "../Homepage/Menu/Categories";
import {Shops} from "../Homepage/Menu/Shops";
import {AdvancedSearch} from "./AdvancedSearch";
import {EbayItems} from "./Items/EbayItems";
import {RepositoryFactory} from "../../services/repositoryFactory";

export const SearchPage = {
    data: function() {
        return {
            ebayHttpInProgress: false,
        }
    },
    beforeDestroy() {
        this.ebayHttpInProgress = false;

        this.$store.commit('ebaySearchListing', {
            listing: [],
            pagination: {
                limit: 4,
                page: 1,
            }
        });

        this.$store.commit('searchLoading', {
            searchProgress: false,
            ebay: false
        });
    },
    template: `<div id="search_page">
                    <categories-menu></categories-menu>
                    <shops-menu></shops-menu>
                    
                    <advanced-search
                        v-bind:external-search-term="searchTerm"
                        v-on:get-ebay-items="onGetEbayItems">
                    </advanced-search>
                    
                    <ebay-items
                        classList="Item SearchItemItem">
                    </ebay-items>
               </div>`,
    computed: {
        searchTerm: function() {
            return this.$store.state.searchTerm;
        }
    },
    methods: {
        onGetEbayItems(model) {
            if (this.ebayHttpInProgress === false) {
                const searchRepo = RepositoryFactory.create('search');

                this.$store.commit('searchLoading', {
                    searchProgress: true,
                    ebay: false
                });

                searchRepo.searchEbay(model, (response) => {
                    this.$store.commit('ebaySearchListing', {
                        listing: response.collection.data,
                        pagination: response.collection.pagination,
                        model: model,
                    });

                    this.ebayHttpInProgress = false;

                    this.$store.commit('searchLoading', {
                        searchProgress: false,
                        ebay: true
                    });

                    this.scrollIfNotScrolled();
                });

                this.ebayHttpInProgress = true;
            }
        },
        scrollIfNotScrolled() {
            const mm = document.getElementById('main_menu');
            const mh = document.getElementById('main_header');
            const as = document.getElementById("AdvancedSearchId");

            setTimeout(function() {
                window.scrollTo({
                    top: getElementGeometry(mm).height + getElementGeometry(mh).height + getElementGeometry(as).height,
                    behavior: 'smooth',
                });
            }, 1000);
        }
    },
    components: {
        'categories-menu': Categories,
        'shops-menu': Shops,
        'advanced-search': AdvancedSearch,
        'ebay-items': EbayItems
    }
};