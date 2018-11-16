import {RepositoryFactory} from "../../../services/repositoryFactory";
import {SUPPORTED_SITES} from "../../../supportedSites";

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
    template: `<div class="NoItems">{{noItemsFoundTranslation}}</div>`,
    props: ['noItemsFoundTranslation']
};

export const ListingChoiceComponent = {
    data: function () {
        return {
            resolvedSites: {},
            supportedSites: SUPPORTED_SITES
        }
    },
    created() {
        for (const globalIdKey in this.$globalIdInformation.all) {
            if (this.$globalIdInformation.all.hasOwnProperty(globalIdKey)) {
                if (this.supportedSites.has(globalIdKey)) {
                    const site = this.$globalIdInformation.all[globalIdKey];

                    this.resolvedSites[site.global_id] = {
                        siteName: site.site_name
                    }
                }
            }
        }
    },
    template: `
        <div
            class="ListingChoiceComponent">
            
            <input type="hidden" :value="searchInitialiseEvent" />

            <div class="ListingChoiceWrapper">
                <h1 class="Title">{{translationsMap.chooseEbaySite}}</h1>

                <div
                    v-for="(item, globalId, index) in resolvedSites"
                    v-if="item !== null"
                    :key="index">
                    <transition name="fade">
                        <listing-choice
                            v-bind:image="supportedSites.find(globalId).icon"
                            v-bind:site-name="item.site_name"
                            v-on:on-ebay-site-choice="onEbaySiteChoice">
                        </listing-choice>
                    </transition>
                </div>
            </div>
        </div>
    `,
    computed: {
        translationsMap: function() {
            return this.$store.state.translationsMap;
        },

        hasItems: function() {
            const preparedSearchInformation = this.preparedSearchInformation;

            if (typeof preparedSearchInformation === 'undefined' || preparedSearchInformation === null) {
                return false;
            }

            const responses = preparedSearchInformation.responses;

            if (!Array.isArray(responses)) {
                return false;
            }

            for (const r of responses) {
                if (r.resource.data.totalEntries > 0) {
                    return true;
                }
            }

            return false;
        },

        searchInitialiseEvent: function () {
            return this.$store.state.searchInitialiseEvent;
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
        'no-items': NoItems,
    }
};