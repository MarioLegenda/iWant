import {SUPPORTED_SITES} from "../../../global";

export const PreparedSearchInformation = {
    template: `<div class="PreparedSearchInformation">
                  <div class="InfoRow">
                      <span class="InfoTitle">Total products found: </span><span class="Information">{{totalProducts}}</span>
                  </div>
                
                  <div class="InfoRow">
                      <span class="InfoTitle">eBay sites searched: </span><span class="Information">{{successSites}}</span>
                  </div>
                
                  <div class="InfoRow">
                      <span class="InfoTitle">Failed searches: </span><span class="Information">{{failedSites}}</span>
                  </div>
               </div>`,
    computed: {
        totalProducts: function() {
            const preparedSearchInformation = this.preparedSearchInformation.responses;

            let total = 0;
            if (Array.isArray(preparedSearchInformation)) {
                for (const response of preparedSearchInformation) {
                    if (!response.isError) {
                        const data = response.resource.data;

                        total+=data.totalEntries;
                    }
                }

                return total;
            }

            return total;
        },
        successSites: function() {
            const preparedSearchInformation = this.preparedSearchInformation.responses;

            let siteNames = [];
            if (Array.isArray(preparedSearchInformation)) {
                for (const response of preparedSearchInformation) {
                    if (!response.isError) {
                        const siteName = response.resource.data.globalIdInformation.site_name;

                        siteNames.push(siteName);
                    }
                }
            }

            if (siteNames.length === 0) {
                return 'N/A';
            }

            return siteNames.join(', ');
        },
        failedSites: function() {
            const preparedSearchInformation = this.preparedSearchInformation.responses;

            let siteNames = [];
            if (Array.isArray(preparedSearchInformation)) {
                let globalIds = [];
                for (const response of preparedSearchInformation) {
                    if (!response.isError) {
                        globalIds.push(response.resource.data.globalIdInformation.global_id);
                    }
                }
                
                for (let supportedSite of SUPPORTED_SITES.sites) {
                    if (!globalIds.includes(supportedSite.globalId)) {
                        siteNames.push(this.$globalIdInformation.all[supportedSite.globalId.toLowerCase()].site_name);
                    }
                }
            }

            if (siteNames.length === 0) {
                return 'None';
            }

            return siteNames.join(', ');
        },
        preparedSearchInformation: function() {
            return this.$store.state.preparedSearchInformation;
        }
    }
};