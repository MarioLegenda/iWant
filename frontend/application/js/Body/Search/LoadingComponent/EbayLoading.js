import {SUPPORTED_SITES} from "../../../global";

export const EbayLoading = {
    data: function() {
        return {
            information: {},
            supportedSites: SUPPORTED_SITES,
            loadedSites: [],
        }
    },
    created() {
        for (const site of this.supportedSites.sites) {
            this.information[site.globalId] = {};
        }

        for (const globalId in this.$globalIdInformation.all) {
            if (this.$globalIdInformation.all.hasOwnProperty(globalId)) {
                const uGlobalId = globalId.toUpperCase();

                if (this.information.hasOwnProperty(uGlobalId)) {
                    this.information[uGlobalId].icon = SUPPORTED_SITES.find(uGlobalId).icon;
                    this.information[uGlobalId].globalId = uGlobalId;
                    this.information[uGlobalId].isLoaded = false;
                }
            }
        }
    },
    computed: {
        preparedEbayRequestEvent: function() {
            if (this.searchInitialiseEvent.initialised) {
                const preparedEbayRequestEvent = this.$store.state.preparedEbayRequestEvent;

                if (preparedEbayRequestEvent !== null && typeof preparedEbayRequestEvent !== 'undefined') {
                    this.information[preparedEbayRequestEvent.globalId].isLoaded = true;
                }
            }

            return this.$store.state.preparedEbayRequestEvent;
        },
        searchInitialiseEvent: function() {
            return this.$store.state.searchInitialiseEvent;
        }
    },
    template: `
            <div class="EbayLoading">
                <input type="hidden" :value="preparedEbayRequestEvent" />
                
                <h1 class="Header"><i class="fa fa-circle-notch fa-spin"></i>Searching eBay sites</h1>
                <div
                    v-for="(item, globalId, index) in information" 
                    v-if="supportedSites.has(item.globalId)"
                    :key="index"
                    class="ImageWrapper">
                    
                    <transition name="fade"><div v-if="!item.isLoaded" class="ImageHider"></div></transition>
                    
                    <img class="Image" :src="item.icon" />
                </div>
            </div>
    `,
};