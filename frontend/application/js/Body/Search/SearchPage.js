import {Categories} from "../Homepage/Menu/Categories";
import {Shops} from "../Homepage/Menu/Shops";
import {AdvancedSearch} from "./AdvancedSearch";
import {EbayItems} from "./Items/EbayItems";
import {RepositoryFactory} from "../../services/repositoryFactory";
import {EtsyItems} from "./Items/EtsyItems";
import {MarketplaceChoice} from "./MarketplaceChoice";

export const SearchPage = {
    data: function() {
        return {
            currentEbayGlobalId: null,
            ebayHttpInProgress: false,
            showMarketplaceChoices: false,
            foundEbayGlobalIds: [],
            marketplaceChoices: {
                ebay: false,
                etsy: false,
            },
        }
    },
    beforeDestroy() {
        this.fullSearchComponentReset();
    },
    template: `<div id="search_page">
                    <input type="hidden" :value="searchLoading" />
                    <advanced-search
                        v-bind:external-search-term="searchTerm"
                        v-on:get-ebay-items="onGetEbayItems"
                        v-on:get-etsy-items="onGetEtsyItems">
                    </advanced-search>
                    
                    <marketplace-choice
                        v-if="showMarketplaceChoices"
                        v-bind:ebay-global-ids="foundEbayGlobalIds"
                        v-on:on-choice="onMarketplaceChoice">
                    </marketplace-choice>
                    
                    <transition name="fade">
                        <ebay-items
                            :key="1"
                            v-bind:currentGlobalId="currentEbayGlobalId"
                            v-on:on-global-ids-computed="onEbayGlobalIdsComputed"
                            v-show="marketplaceChoices.ebay"
                            classList="Item SearchItemItem">
                        </ebay-items>
                    </transition>
                    
                    <transition name="fade">
                        <etsy-items
                            :key="2"
                            v-show="marketplaceChoices.etsy"
                            classList="Item SearchItemItem">
                        </etsy-items>
                    </transition>
               </div>`,
    computed: {
        searchTerm: function() {
            return this.$store.state.searchTerm;
        },

        searchLoading: function() {
            const searchLoading = this.$store.state.searchLoading;

            if (searchLoading.ebay === true && searchLoading.etsy === true) {
                this.showMarketplaceChoices = true;
            }

            return this.$store.state.searchLoading;
        },
    },
    methods: {
        onEbayGlobalIdsComputed(globalIds) {
            if (this.foundEbayGlobalIds.length === 0) {
                this.foundEbayGlobalIds = globalIds;
            }
        },

        onMarketplaceChoice(marketplace) {
            this.resetMarketplaceChoices(marketplace);

            if (marketplace.globalId !== null) {
                this.currentEbayGlobalId = marketplace.globalId;
            }

            this.marketplaceChoices[marketplace.marketplace] = true;
        },

        onGetEtsyItems(model) {
            this.dataReset('etsySearchListing');
            this.resetChoices();

            setTimeout(() => {
                const searchRepo = RepositoryFactory.create('search');

                this.$store.commit('searchLoading', {
                    searchProgress: true,
                    etsy: false,
                });

                searchRepo.searchEtsy(model, (response) => {
                    this.$store.commit('etsySearchListing', {
                        listing: response.collection.data,
                        pagination: response.collection.pagination,
                        model: model,
                    });

                    this.$store.commit('searchLoading', {
                        etsy: true
                    });
                });
            }, 500);
        },

        onGetEbayItems(model) {
            this.dataReset('ebaySearchListing');
            this.resetChoices();

            if (this.ebayHttpInProgress === false) {
                const searchRepo = RepositoryFactory.create('search');

                this.$store.commit('searchLoading', {
                    searchProgress: true,
                    ebay: false,
                });

                setTimeout(() => {
                    searchRepo.searchEbay(model, (response) => {
                        this.$store.commit('ebaySearchListing', {
                            listing: response.collection.views.globalIdView,
                            pagination: response.collection.pagination,
                            model: model,
                        });

                        this.ebayHttpInProgress = false;

                        this.$store.commit('searchLoading', {
                            ebay: true
                        });
                    });
                }, 500);

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
        },

        resetMarketplaceChoices(marketplace) {
            for (let choice in this.marketplaceChoices) {
                if (this.marketplaceChoices.hasOwnProperty(choice)) {
                    if (choice !== marketplace.marketplace) {
                        this.marketplaceChoices[choice] = false;
                    }
                }
            }
        },

        resetChoices() {
            for (let choice in this.marketplaceChoices) {
                if (this.marketplaceChoices.hasOwnProperty(choice)) {
                    this.marketplaceChoices[choice] = false;

                }
            }

            if (this.currentEbayGlobalId !== null) this.currentEbayGlobalId = null;
            if (this.showMarketplaceChoices) this.showMarketplaceChoices = false;
            if (this.foundEbayGlobalIds.length > 0) this.foundEbayGlobalIds = [];
        },

        dataReset(property) {
            this.ebayHttpInProgress = false;

            this.$store.commit(property, {
                listing: [],
                pagination: {
                    limit: 4,
                    page: 1,
                },
            });

            this.$store.commit('searchLoading', {
                searchProgress: false,
                ebay: false,
                etsy: false,
            });
        },

        fullSearchComponentReset() {
            this.dataReset('ebaySearchListing');
            this.dataReset('etsySearchListing');
            this.resetMarketplaceChoices({marketplace: 'etsy'});
            this.resetMarketplaceChoices({marketplace: 'ebay'});
            this.resetChoices();
        }
    },
    components: {
        'categories-menu': Categories,
        'shops-menu': Shops,
        'advanced-search': AdvancedSearch,
        'ebay-items': EbayItems,
        'etsy-items': EtsyItems,
        'marketplace-choice': MarketplaceChoice,
    }
};