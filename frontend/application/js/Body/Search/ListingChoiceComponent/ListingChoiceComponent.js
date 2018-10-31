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
            this.$emit('on-ebay-site-choice', this.preparedData);
        }
    }
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
        </div>
    `,
    computed: {
        preparedEbayRequestEvent: function () {
            const preparedEbayRequestEvent = this.$store.state.preparedEbayRequestEvent;

            if (typeof preparedEbayRequestEvent === 'object' && preparedEbayRequestEvent !== null) {
                if (preparedEbayRequestEvent.isError) {
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

            searchRepo.getPreparedEbaySearch({
                uniqueName: preparedData.uniqueName,
                lowestPrice: this.filtersEvent.lowestPrice,
                pagination: {
                    limit: 8,
                    page: 1
                }
            }, (r) => {
                const data = r.collection.data;

                this.$store.commit('ebaySearchListing', {
                    items: data,
                    pagination: r.collection.pagination,
                    preparedData: preparedData
                });
            });
        }
    },
    components: {
        'listing-choice': ListingChoice,
        'prepared-search-information': PreparedSearchInformation,
    }
};