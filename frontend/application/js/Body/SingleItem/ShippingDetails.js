export const ShippingDetails = {
    data: function() {
        return {
            countries: null,
            selectedIncluded: null,
            isCountryExcluded: false,
        }
    },
    props: ['itemId', 'shipsToLocations', 'excludeShipToLocations'],
    template: `<div class="ShippingDetailsWrapper">
                   <modal name="shipping-details-modal">
                       <div class="ShippingDetailsImplementation">
                           <div class="ActionContainer">
                               <div class="SelectCountryContainer">
                                   <span class="TextArea">Select a country to ship to: </span>
                                   <select @change="onCountryChange" v-model="selectedIncluded" class="SelectArea">
                                       <option
                                           v-for="item in countries"
                                           :value="item.alpha2Code"
                                           :key="item.id">{{item.name}}
                                       </option>
                                   </select>
                                   
                                   <div v-if="isCountryExcluded" class="ExcludedShippingErrorMessage">
                                       <p>This item does not ship to selected country</p>
                                   </div>
                               </div>
                           </div>
                       
                           <div class="InformationContainer">
                       
                           </div>
                       </div>
                   </modal>
               </div>`,
    created() {
        this.$repository.AppRepository.getCountries(null, (r) => {
            this.countries = r.collection.data;
            this.selectedIncluded = this.countries[0].alpha2Code;
        });
    },

    computed: {
    },

    methods: {
        onCountryChange() {
            this.isCountryExcluded = false;

            if (this.excludeShipToLocations.includes(this.selectedIncluded)) {
                this.isCountryExcluded = true;

                return null;
            }

            this.$repository.SingleItemRepository.getShippingCosts({
                itemId: this.itemId,
                locale: this.$localeInfo.locale,
                destinationCountryCode: this.selectedIncluded
            }, (r) => {
                if (r.statusCode === 404) {
                    this.isCountryExcluded = true;
                }
            });
        },
    },
};