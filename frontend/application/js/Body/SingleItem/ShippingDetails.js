import {NameValueContainer} from "./NameValueContainer";

export const ShippingDetails = {
    data: function() {
        return {
            countries: null,
            selectedIncluded: null,
            isCountryExcluded: false,
            shippingInfo: null,
            selectedCountry: null,
        }
    },
    props: ['itemId', 'shipsToLocations', 'excludeShipToLocations'],
    template: `<div class="ShippingDetailsWrapper">
                   <modal 
                       name="shipping-details-modal" 
                       height="auto" 
                       :minHeight="500">
                       
                       <div class="ShippingDetailsImplementation">
                           <div class="ActionContainer">
                               <div class="SelectCountryContainer">
                                   <span class="TextArea">Select a country: </span>
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
                       
                           <div class="InformationContainer" v-if="shippingInfo">
                               <div class="DataRow">
                                   <h1>{{headerInfo}}</h1>
                                   
                                   <h2>Summary</h2>
                                   
                                   <div class="DataContainer">
                                       <div class="NameValueContainerWrapper">
                                           <name-value-container
                                               name="Shipping service name: "
                                               v-bind:value="shippingInfo.shippingCostsSummary.shippingServiceName">
                                           </name-value-container>
                                       </div>
                                       
                                       <div class="NameValueContainerWrapper">
                                           <name-value-container
                                               name="Shipping service type: "
                                               v-bind:value="shippingInfo.shippingCostsSummary.shippingType">
                                           </name-value-container>
                                       </div>
                                       
                                       <div class="NameValueContainerWrapper">
                                           <name-value-container
                                               name="Cost: "
                                               v-bind:value="viewPrice(shippingInfo.shippingCostsSummary.shippingServiceCost)">
                                           </name-value-container>
                                       </div>
                                       
                                       <div class="NameValueContainerWrapper" v-if="shippingInfo.shippingCostsSummary.importCharge">
                                           <name-value-container
                                               name="Import charge: "
                                               v-bind:value="viewPrice(shippingInfo.shippingCostsSummary.importCharge)">
                                           </name-value-container>
                                       </div>
                                       
                                       <div class="NameValueContainerWrapper" v-if="shippingInfo.shippingCostsSummary.insuranceCost">
                                           <name-value-container
                                               name="Insurance cost: "
                                               v-bind:value="viewPrice(shippingInfo.shippingCostsSummary.insuranceCost)">
                                           </name-value-container>
                                       </div>
                                       
                                       <!-- HANDLE INSURANCE OPTION AS A TRANSLATION ON THE FRONTEND -->
                                       <div class="NameValueContainerWrapper" v-if="shippingInfo.shippingCostsSummary.insuranceOption">
                                           <name-value-container
                                               name="Insurance option: "
                                               v-bind:value="shippingInfo.shippingCostsSummary.insuranceOption">
                                           </name-value-container>
                                       </div>
                                   </div>
                               </div>
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
        headerInfo: function() {
            if (this.selectedCountry !== null) {
                return `Shipping information for ${this.selectedCountry.name}`;
            }

            return `Shipping information`
        }
    },

    methods: {
        onCountryChange() {
            this.isCountryExcluded = false;
            this.shppingInfo = null;

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
                } else if (r.statusCode === 200) {
                    this.selectedCountry = this._findCountry(this.selectedIncluded);
                    this.shippingInfo = r.resource.data;
                    console.log(this.shippingInfo);
                }
            });
        },

        viewPrice(price) {
            if (price.price === 0) {
                return 'Free';
            }

            return `${price.price} ${price.currency}`
        },

        _findCountry(alpha2Code) {
            for (const c of this.countries) {
                if (c.alpha2Code === alpha2Code) {
                    return c;
                }
            }

            return null;
        }

    },

    components: {
        'name-value-container': NameValueContainer,
    }
};