import {supportedSites} from "../../../global";

export const EbayLoading = {
    data: function() {
        return {
            information: {},
            supportedSites: supportedSites,
            loadedSites: [],
        }
    },
    created() {
        for (const site in supportedSites) {
            this.information[supportedSites[site].toUpperCase()] = {};
        }

        for (const globalId in this.$globalIdInformation.all) {
            if (this.$globalIdInformation.all.hasOwnProperty(globalId)) {
                const siteInfo = this.$globalIdInformation.all[globalId];
                const uGlobalId = globalId.toUpperCase();

                if (this.information.hasOwnProperty(uGlobalId)) {
                    this.information[uGlobalId].icon = `/images/country_icons/${globalId}.svg`;
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
                    this.information[preparedEbayRequestEvent].isLoaded = true;
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
                <div
                    v-for="(item, globalId, index) in information" 
                    v-if="supportedSites.includes(item.globalId)"
                    :key="index"
                    class="ImageWrapper">
                    
                    <div v-if="!item.isLoaded" class="ImageHider"></div>
                    <img class="Image" :src="item.icon" />
                </div>
            </div>
    `,
};