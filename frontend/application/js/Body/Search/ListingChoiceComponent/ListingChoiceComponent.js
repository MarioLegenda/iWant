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
            class="ListingChoiceComponent" id="ListingChoiceComponentId">

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
        getModel: (prev, next) => {
        },

        getFilters: (prev, next) => {
        }
    },
    computed: {
        translationsMap: function() {
            return this.$store.state.translationsMap;
        },

        searchInitialiseEvent: function () {
            return this.$store.state.searchInitialiseEvent;
        },

        getModel: function() {
            return this.$store.getters.getModel;
        },

        getFilters: function() {
            return this.$store.getters.getFilters;
        }
    },
    methods: {
        onEbaySiteChoice: function (globalId) {
            let model = Object.assign({}, this.getModel);

            model.filters = this.getFilters;
            model.globalId = globalId;

            this.$store.dispatch('totalListingUpdate', model);

            const timeout = (this.$isMobile) ? 1000 : 0;

            setTimeout(() => scrollToElement(document.getElementById('EbayResultsLoadingId'), 200), timeout);
        }
    },
    components: {
        'listing-choice': ListingChoice,
        'no-items': NoItems,
    }
};