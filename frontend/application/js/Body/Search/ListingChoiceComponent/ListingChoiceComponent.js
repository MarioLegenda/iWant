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
    props: ['globalId', 'image', 'siteName'],
    methods: {
        onListingChoice: function() {
            this.$store.commit('ebaySearchListingLoading', true);

            this.$emit('on-ebay-site-choice', this.globalId);
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
            resolvedSites: null,
            supportedSites: SUPPORTED_SITES
        }
    },
    created() {
        if (this.resolvedSites === null) {
            this.resolvedSites = {};

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
                            v-bind:site-name="item.siteName"
                            v-bind:global-id="globalId.toUpperCase()"
                            v-on:on-ebay-site-choice="onEbaySiteChoice">
                        </listing-choice>
                    </transition>
                </div>
            </div>
        </div>
    `,
    watch: {
        modelWasUpdated: (prev, next) => {
        }
    },
    computed: {
        translationsMap: function() {
            return this.$store.state.translationsMap;
        },

        searchInitialiseEvent: function () {
            return this.$store.state.searchInitialiseEvent;
        },

        filtersEvent: function() {
            return this.$store.state.filtersEvent;
        },

        modelWasUpdated: function() {
            return this.$store.state.modelWasUpdated;
        }
    },
    methods: {
        onEbaySiteChoice: function (globalId) {
            this.$store.commit('ebaySearchListing', {
                siteInformation: null,
                items: null,
            });

            let model = Object.assign({}, this.modelWasUpdated);

            model.filters = this.filtersEvent;
            model.globalId = globalId;
            model.range = {from: 1, to: 1};

            const searchRepo = RepositoryFactory.create('search');

            searchRepo.optionsForProductListing(model, (r) => {
                const data = r.resource.data;

                switch (data.method) {
                    case 'POST':
                            searchRepo.postPrepareSearchProducts(JSON.stringify({
                                searchData: model,
                            })).then(() => {
                                searchRepo.getProducts(model).then((r) => {
                                    this.$store.commit('ebaySearchListing', r.collection.data);
                                    this.$store.commit('ebaySearchListingLoading', false);
                                });
                            });
                        break;
                    case 'GET':
                        searchRepo.getProducts(model, (r) => {
                            this.$store.commit('ebaySearchListing', r.collection.data);
                            this.$store.commit('ebaySearchListingLoading', false);
                        });
                        break;
                    default:
                        throw new Error(`Invalid option for search listing given. Method can only be POST or GET, ${data.method} given`)
                }
            });
        }
    },
    components: {
        'listing-choice': ListingChoice,
        'no-items': NoItems,
    }
};