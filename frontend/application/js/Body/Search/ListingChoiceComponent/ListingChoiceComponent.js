import {SUPPORTED_SITES} from "../../../global";
import {RepositoryFactory} from "../../../services/repositoryFactory";
import {PreparedSearchInformation} from "./PreparedSearchInformation";

const ListingChoice = {
    template: `
        <div @click="onListingChoice" class="ListingChoice">
        
            <p class="SiteName">{{siteName}}</p>

            <div class="ImageWrapper">
                <img class="Image" :src="image" />
            </div>
        </div>
    `,
    props: ['image', 'siteName', 'preparedData'],
    methods: {
        onListingChoice: function() {
            this.$store.commit('ebaySearchListingLoading', true);

            this.$emit('on-ebay-site-choice', this.preparedData);
        }
    }
};

const NoItems = {
    template: `<div class="NoItems">No items found</div>`
};

export const ListingChoiceComponent = {
    data: function () {
        return {
            resolvedSites: {},
            supportedSites: SUPPORTED_SITES
        }
    },
    template: `
        <div 
            class="ListingChoiceComponent">
            
            <input type="hidden" :value="preparedEbayRequestEvent" />
            <input type="hidden" :value="searchInitialiseEvent" />
            
            <prepared-search-information></prepared-search-information>
            
            <div class="ListingChoiceWrapper">
                <h1 class="Title">Choose eBay site</h1>

                <div
                    v-for="(item, globalId, index) in resolvedSites"
                    v-if="item !== null"
                    :key="index">
                    <transition name="fade">
                        <listing-choice
                            v-bind:image="supportedSites.find(globalId).icon"
                            v-bind:site-name="item.preparedData.globalIdInformation.site_name"
                            v-bind:prepared-data="item.preparedData"
                            v-on:on-ebay-site-choice="onEbaySiteChoice">
                        </listing-choice>
                    </transition>
                </div>
                
                <no-items v-if="hasItems === false"></no-items>
            </div>
        </div>
    `,
    computed: {
        preparedSearchInformation: function() {
            const preparedSearchInformation = this.$store.state.preparedSearchInformation;

            if (typeof preparedSearchInformation === 'undefined' || preparedSearchInformation === null) {
                return null;
            }

            return this.$store.state.preparedSearchInformation;
        },
        hasItems: function() {
            const preparedSearchInformation = this.preparedSearchInformation;

            if (typeof preparedSearchInformation === 'undefined' || preparedSearchInformation === null) {
                return null;
            }

            const responses = preparedSearchInformation.responses;

            if (!Array.isArray(responses)) {
                return null;
            }

            for (const r of responses) {
                if (r.resource.data.totalEntries > 0) {
                    return true;
                }
            }

            return false;
        },
        preparedEbayRequestEvent: function () {
            const preparedEbayRequestEvent = this.$store.state.preparedEbayRequestEvent;

            if (typeof preparedEbayRequestEvent === 'object' && preparedEbayRequestEvent !== null) {
                if (preparedEbayRequestEvent.isError) {
                    return preparedEbayRequestEvent;
                }

                if (preparedEbayRequestEvent.preparedData.totalEntries === 0) {
                    return preparedEbayRequestEvent;
                }

                this.resolvedSites[preparedEbayRequestEvent.preparedData.globalId] = preparedEbayRequestEvent;

                return preparedEbayRequestEvent;
            }

            return preparedEbayRequestEvent;
        },

        searchInitialiseEvent: function () {
            const searchInitialiseEvent = this.$store.state.searchInitialiseEvent;

            if (searchInitialiseEvent.initialised) {
                this.resolvedSites = {};
            }

            return searchInitialiseEvent;
        },

        filtersEvent: function() {
            return this.$store.state.filtersEvent;
        }
    },
    methods: {
        onEbaySiteChoice: function (preparedData) {
            const searchRepo = RepositoryFactory.create('search');

            this.$store.commit('ebaySearchListing', null);

            searchRepo.getPreparedEbaySearch({
                uniqueName: preparedData.uniqueName,
                globalId: preparedData.globalId,
                locale: this.$localeInfo.locale,
                lowestPrice: this.filtersEvent.lowestPrice,
                pagination: {
                    limit: 8,
                    page: 1
                }
            }, (r) => {
                const data = r.collection.data;

                this.$store.commit('ebaySearchListingLoading', false);

                this.$store.commit('ebaySearchListing', {
                    items: data,
                    pagination: r.collection.pagination,
                    preparedData: preparedData,
                });
            });
        }
    },
    components: {
        'listing-choice': ListingChoice,
        'prepared-search-information': PreparedSearchInformation,
        'no-items': NoItems,
    }
};