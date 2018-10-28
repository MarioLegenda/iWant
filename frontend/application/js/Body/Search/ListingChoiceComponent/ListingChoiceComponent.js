import {SUPPORTED_SITES} from "../../../global";

export const ListingChoiceComponent = {
    data: function() {
        return {
            supportedSites: {},
        }
    },
    created() {
        for (const site of SUPPORTED_SITES.sites) {
            this.supportedSites[site.globalId] = null;
        }
    },
    template: `
        <div class="ListingChoiceComponent">
            
        </div>
    `,
    computed: {
        preparedEbayRequestEvent: function() {
            const preparedEbayRequestEvent = this.$store.state.preparedEbayRequestEvent;

            this.supportedSites[preparedEbayRequestEvent.globalId] = preparedEbayRequestEvent.resolved;

            return preparedEbayRequestEvent;
        }
    }
};