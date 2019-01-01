import {NameValueContainer} from "./NameValueContainer";
import {ActionNameValueContainer} from "./ActionNameValueContainer";
import Autocomplete from 'vuejs-auto-complete';

const reusableMethods = {
    methods: {
        viewPrice(price) {
            if (price.price === 0) {
                return 'Free';
            }

            return `${price.price} ${price.currency}`
        },
    }
};

const AdditionalShippingDetails = {
    mixins: [reusableMethods],
    props: ['shippingDetails', 'country'],
    template: `<div>
                  <h2>Additional details</h2>
                  
                  <div class="DataContainer">
                      <div class="NameValueContainerWrapper" v-if="shippingDetails.shippingRateErrorMessage">
                          <name-value-container
                              name="Shipping error message: "
                              v-bind:value="viewPrice(shippingDetails.shippingRateErrorMessage)">
                          </name-value-container>
                      </div>
                      
                      <div class="NameValueContainerWrapper" v-if="shippingDetails.cashOnDeliveryCost">
                          <action-name-value-container
                              name="Cash-on-delivery cost: "
                              v-bind:value="viewPrice(shippingDetails.cashOnDeliveryCost)"
                              v-bind:description="false">
                                
                              <div slot="description">
                                  <p class="NameValueDescription">Only applicable to the Italy site, and is only returned if cash-on-delivery is the only available logistics type for the listing</p>
                              </div>

                          </action-name-value-container>
                      </div>
                      
                      <div class="NameValueContainerWrapper" v-if="shippingDetails.insuranceCost">
                          <name-value-container
                              name="Insurance cost: "
                              v-bind:value="viewPrice(shippingDetails.insuranceCost)">
                          </name-value-container>
                      </div>
                                       
                      <!-- HANDLE INSURANCE OPTION AS A TRANSLATION ON THE FRONTEND -->
                      <div class="NameValueContainerWrapper" v-if="shippingDetails.insuranceOption">
                          <name-value-container
                              name="Insurance option: "
                              v-bind:value="shippingDetails.insuranceOption">
                          </name-value-container>
                      </div>
                      
                  <h2>International shipping</h2>
                  
                  <div class="DataContainer">
                      <action-name-value-container
                          v-for="item in shippingDetails.internationalShippingServiceOption"
                          :key="item.shippingServiceName"
                          v-bind:value="item.shippingServiceName"
                          :description="false">
                          
                          <div slot="description">
                          
                              <div class="NameValueDescription">
                              
                                  <div class="InnerNameValueContainerWrapper">
                                      <name-value-container
                                          name="Shipping service additional cost: "
                                          v-bind:value="viewPrice(item.shippingServiceCost)">
                                      </name-value-container>
                                  </div>
                              
                                  <div class="InnerNameValueContainerWrapper">
                                      <name-value-container
                                          name="Maximum delivery date: "
                                          v-bind:value="item.estimatedDeliveryMaxTime.dateTime">
                                      </name-value-container>
                                  </div>
                      
                                  <div class="InnerNameValueContainerWrapper">
                                      <name-value-container
                                          name="Minimum delivery date: "
                                          v-bind:value="item.estimatedDeliveryMinTime.dateTime">
                                      </name-value-container>
                                  </div>
                                  
                                  <div class="InnerNameValueContainerWrapper" v-if="item.importCharge">
                                      <name-value-container
                                          name="Import charge: "
                                          v-bind:value="viewPrice(item.importCharge)">
                                      </name-value-container>
                                  </div>
                                  
                                  <div class="InnerNameValueContainerWrapper" v-if="item.shippingInsuranceCost">
                                      <name-value-container
                                          name="Shipping insurance cost: "
                                          v-bind:value="viewPrice(item.shippingInsuranceCost)">
                                      </name-value-container>
                                  </div>
                                  
                                  <div class="InnerNameValueContainerWrapper" v-if="item.shippingServiceAdditionalCost">
                                      <name-value-container
                                          name="Shipping service additional cost: "
                                          v-bind:value="viewPrice(item.shippingServiceAdditionalCost)">
                                      </name-value-container>
                                  </div>
                              </div>
                              
                          </div>
                      </action-name-value-container>
                  </div>
                  </div>
                  
               </div>
                    `,
    components: {
        'name-value-container': NameValueContainer,
        'action-name-value-container': ActionNameValueContainer,
    }
};

const Summary = {
    mixins: [reusableMethods],
    props: ['shippingCostsSummary'],
    template: `                   <div class="DataContainer">
                                       <div class="NameValueContainerWrapper">
                                           <name-value-container
                                               name="Shipping service name: "
                                               v-bind:value="shippingCostsSummary.shippingServiceName">
                                           </name-value-container>
                                       </div>
                                       
                                       <div class="NameValueContainerWrapper">
                                           <name-value-container
                                               name="Shipping service type: "
                                               v-bind:value="shippingCostsSummary.shippingType">
                                           </name-value-container>
                                       </div>
                                       
                                       <div class="NameValueContainerWrapper">
                                           <name-value-container
                                               name="Cost: "
                                               v-bind:value="viewPrice(shippingCostsSummary.shippingServiceCost)">
                                           </name-value-container>
                                       </div>
                                       
                                       <div class="NameValueContainerWrapper" v-if="shippingCostsSummary.importCharge">
                                           <name-value-container
                                               name="Import charge: "
                                               v-bind:value="viewPrice(shippingCostsSummary.importCharge)">
                                           </name-value-container>
                                       </div>
                                       
                                       <div class="NameValueContainerWrapper" v-if="shippingCostsSummary.insuranceCost">
                                           <name-value-container
                                               name="Insurance cost: "
                                               v-bind:value="viewPrice(shippingCostsSummary.insuranceCost)">
                                           </name-value-container>
                                       </div>
                                       
                                       <!-- HANDLE INSURANCE OPTION AS A TRANSLATION ON THE FRONTEND -->
                                       <div class="NameValueContainerWrapper" v-if="shippingCostsSummary.insuranceOption">
                                           <name-value-container
                                               name="Insurance option: "
                                               v-bind:value="shippingCostsSummary.insuranceOption">
                                           </name-value-container>
                                       </div>
                                  </div>`,
    components: {
        'name-value-container': NameValueContainer,
    }
};

export const AutocompleteWrapper = {
    data: function() {
        return {
            countries: [],
        }
    },
    template: `<div class="AutocompleteWrapper">
                   <autocomplete
                       :source="countries"
                       placeholder="Select a country"
                       inputClass="AutocompleteInput"
                       v-on:selected="onCountrySelected">
                   </autocomplete>
               </div>`,
    created() {
        this.$repository.AppRepository.getCountries(null, (r) => {
            this.countries = r.collection.data.filterInternal((c) => {
                return {id: c.id, name: c.name, alpha2Code: c.alpha2Code};
            });
        });
    },
    methods: {
        onCountrySelected(s) {
            this.$emit('selected-country', s.selectedObject);
        }
    },
    components: {
        'autocomplete': Autocomplete,
    }
};

export const ShippingDetails = {
    data: function() {
        return {
            countries: null,
            selectedIncluded: null,
            isCountryExcluded: false,
            shippingInfo: null,
            selectedCountry: null,
            error: false,
        }
    },
    props: ['itemId', 'shipsToLocations', 'excludeShipToLocations'],
    template: `<div class="ShippingDetailsWrapper">
                   <modal 
                       name="shipping-details-modal"
                       width="70%"
                       height="auto"
                       :minHeight="500"
                       :scrollable="true"
                       @before-close="onBeforeModalClose">
                       
                       <div class="ShippingDetailsImplementation">
                           <div class="ActionContainer">
                               <div class="SelectCountryContainer">                                   
                                   <autocomplete-wrapper
                                       v-on:selected-country="onSelectedCountry">
                                       
                                   </autocomplete-wrapper>
                                   
                                   <div v-if="isCountryExcluded" class="ExcludedShippingErrorMessage">
                                       <p>This item does not ship to selected country</p>
                                   </div>
                                   
                                   <div v-if="error" class="ExcludedShippingErrorMessage">
                                       <p>There has been an error. I am working on it. Please, try reloading the page and try again.</p>
                                   </div>
                               </div>
                           </div>
                       
                           <div class="InformationContainer" v-if="shippingInfo">
                               <div class="DataRow">
                                   <h1>{{headerInfo}}</h1>
                                   
                                   <h2>Summary</h2>
                                   
                                   <shipping-summary :shipping-costs-summary="shippingInfo.shippingCostsSummary"></shipping-summary>
                                  
                                   <additional-shipping-details 
                                       v-if="shippingInfo.shippingDetails"
                                       :shipping-details="shippingInfo.shippingDetails"
                                       :country="selectedCountry">
                                   </additional-shipping-details>
                               </div>
                           </div>
                       </div>
                   </modal>
               </div>`,
    created() {
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
        onSelectedCountry(country) {
            this.isCountryExcluded = false;
            this.error = false;

            if (Array.isArray(this.excludeShipToLocations)) {
                if (this.excludeShipToLocations.includes(this.selectedIncluded)) {
                    setTimeout(() => {
                        this.isCountryExcluded = true;
                    }, 500);

                    return null;
                }
            }

            this.$repository.SingleItemRepository.getShippingCosts({
                itemId: this.itemId,
                locale: this.$localeInfo.locale,
                destinationCountryCode: country.alpha2Code,
            }, (r) => {
                if (r.statusCode === 404) {
                    this.isCountryExcluded = true;
                } else if (r.statusCode === 200) {
                    this.selectedCountry = country;
                    this.shippingInfo = r.resource.data;
                } else {
                    this.error = true;
                }
            });
        },

        onBeforeModalClose() {
            this.isCountryExcluded = false;

            if (this.selectedCountry !== null) {
                this.selectedIncluded = this.selectedCountry.alpha2Code;
            }
        },
    },

    components: {
        'name-value-container': NameValueContainer,
        'shipping-summary': Summary,
        'additional-shipping-details': AdditionalShippingDetails,
        'autocomplete-wrapper': AutocompleteWrapper,
    }
};