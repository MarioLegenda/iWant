import {SUPPORTED_SITES} from "../../../supportedSites";

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
            if (site.enabled) {
                this.information[site.globalId] = {};
            }
        }

        for (const globalId in this.$globalIdInformation.all) {
            if (this.$globalIdInformation.all.hasOwnProperty(globalId)) {
                const uGlobalId = globalId.toUpperCase();

                if (this.information.hasOwnProperty(uGlobalId)) {
                    this.information[uGlobalId].icon = SUPPORTED_SITES.find(uGlobalId).icon;
                    this.information[uGlobalId].globalId = uGlobalId;
                    this.information[uGlobalId].isLoaded = false;
                    this.information[uGlobalId].isError = false;
                }
            }
        }
    },
    computed: {
        preparedEbayRequestEvent: function() {
            if (this.searchInitialiseEvent.initialised) {
                const preparedEbayRequestEvent = this.$store.state.preparedEbayRequestEvent;

                if (preparedEbayRequestEvent !== null && typeof preparedEbayRequestEvent !== 'undefined') {
                    if (preparedEbayRequestEvent.isError) {
                        this.information[preparedEbayRequestEvent.globalId].isError = preparedEbayRequestEvent.isError;
                        this.information[preparedEbayRequestEvent.globalId].isLoaded = true;
                    } else if (!preparedEbayRequestEvent.isError) {
                        this.information[preparedEbayRequestEvent.preparedData.globalId].isError = preparedEbayRequestEvent.isError;
                        this.information[preparedEbayRequestEvent.preparedData.globalId].isLoaded = true;
                    }
                }
            }

            return this.$store.state.preparedEbayRequestEvent;
        },
        searchInitialiseEvent: function() {
            return this.$store.state.searchInitialiseEvent;
        },
        translationsMap: function() {
            return this.$store.state.translationsMap;
        }
    },
    template: `
            <div class="EbayLoading">
                <input type="hidden" :value="preparedEbayRequestEvent" />
                
                <h1 class="Header"><i class="fa fa-circle-notch fa-spin"></i>{{translationsMap.searchingEbaySites}}</h1>
                
                <div
                    v-for="(item, globalId, index) in information" 
                    v-if="supportedSites.has(item.globalId)"
                    :key="index"
                    class="ImageWrapper">
                    
                    <transition name="fade"><div v-if="!item.isLoaded && !item.isError" class="ImageHider"></div></transition>
                    <transition name="fade"><div v-if="item.isLoaded && item.isError" class="ErrorOccurred"><i class="fas fa-times"></i></div></transition>
                    <img class="Image" :src="item.icon" />
                </div>
            </div>
    `,
};