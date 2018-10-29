import {SUPPORTED_SITES} from "../../../global";

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
        }
    }
};

export const ListingChoiceComponent = {
    data: function() {
        return {
            resolvedSites: {},
            supportedSites: SUPPORTED_SITES
        }
    },
    template: `
        <div 
            class="ListingChoiceComponent">
            
            <input type="hidden" :value="preparedEbayRequestEvent" />
            
            <h1 class="Title">Choose your country</h1>
            
            <div
               v-for="(item, globalId, index) in resolvedSites"
               v-if="item !== null"
               :key="index">
                <listing-choice
                    v-bind:image="supportedSites.find(globalId).icon"
                    v-bind:site-name="item.preparedData.globalIdInformation.site_name"
                    v-bind:prepared-data="item.preparedData">
                </listing-choice>
            </div>
        </div>
    `,
    computed: {
        preparedEbayRequestEvent: function() {
            const preparedEbayRequestEvent = this.$store.state.preparedEbayRequestEvent;

            if (typeof preparedEbayRequestEvent === 'object' && preparedEbayRequestEvent !== null) {

                this.resolvedSites[preparedEbayRequestEvent.preparedData.globalId] = preparedEbayRequestEvent;

                return preparedEbayRequestEvent;
            }

            return preparedEbayRequestEvent;
        },

        searchInitialiseEvent: function() {
            const searchInitialiseEvent = this.$store.state.searchInitialiseEvent;

            if (searchInitialiseEvent.initialised === false) {
                this.resolvedSites = {};
            }
        }
    },
    components: {
        'listing-choice': ListingChoice
    }
};