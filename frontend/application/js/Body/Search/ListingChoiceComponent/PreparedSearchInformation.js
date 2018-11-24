export const PreparedSearchInformation = {
    data: function() {
        return {
            totalProducts: 0,
            successSites: [],
            successSitesString: 'N/A',
            failedSites: [],
            failedSitesString: 'None',
        }
    },
    template: `<div class="PreparedSearchInformation">
                  
                  <div class="InfoRow">
                      <span class="InfoTitle">{{translationsMap.searchInformation.totalProductsFound}}</span><span class="Information">{{totalProducts}}</span>
                  </div>
                
                  <div class="InfoRow">
                      <span class="InfoTitle">{{translationsMap.searchInformation.ebaySitesSearched}}</span><span class="Information">{{successSitesString}}</span>
                  </div>
                
                  <div class="InfoRow">
                      <span class="InfoTitle">{{translationsMap.searchInformation.failedSearches}}</span><span class="Information">{{failedSitesString}}</span>
                  </div>
               </div>`,
    methods: {
        calcTotal: function(data) {
            this.totalProducts+=data.totalEntries;
        },
        calcSuccessSites: function(data) {
            this.successSites.push(data.globalIdInformation.site_name);

            this.successSitesString = this.successSites.join(', ');
        },
        calcFailedSites: function(preparedEbayRequestEvent) {
            const globalId = preparedEbayRequestEvent.globalId;

            this.failedSites.push(this.$globalIdInformation.all[globalId.toLowerCase()].site_name);

            if (this.failedSites.length > 0) {
                this.failedSitesString = this.failedSites.join(', ');
            }
        },
    },
    computed: {
        searchInitialiseEvent: function() {
            const searchInitialiseEvent = this.$store.state.searchInitialiseEvent;

            if (searchInitialiseEvent.initialised) {
                this.totalProducts = 0;
                this.successSites = [];
                this.failedSites = [];
                this.successSitesString = 'N/A';
                this.failedSitesString = 'None';
            }

            return searchInitialiseEvent;
        },

        preparedEbayRequestEvent: function() {
            const preparedEbayRequestEvent = this.$store.state.preparedEbayRequestEvent;

            if (preparedEbayRequestEvent === null) {
                return preparedEbayRequestEvent;
            }

            if (!preparedEbayRequestEvent.isError) {
                const preparedData = preparedEbayRequestEvent.preparedData;

                this.calcTotal(preparedData);
                this.calcSuccessSites(preparedData);
            }

            if (preparedEbayRequestEvent.isError) {
                this.calcFailedSites(preparedEbayRequestEvent);
            }

            return preparedEbayRequestEvent;
        },
        translationsMap: function() {
            return this.$store.state.translationsMap;
        }
    }
};