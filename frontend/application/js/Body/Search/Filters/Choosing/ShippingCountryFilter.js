import {AutocompleteWrapper} from "../../../SingleItem/ShippingDetails";

const ShippingCountriesModal = {
    data: function() {
        return {
            countries: [],
        }
    },
    template: `<div class="ShippingDetailsWrapper">
                   <modal 
                       name="shipping-countries-select-modal"
                       width="95%"
                       height="auto"
                       :minHeight="200"
                       :scrollable="false">
                       
                       <div class="ShippingDetailsImplementation">
                           <i @click="closeModal" class="fas fa-times CloseButton"></i>
                           <div class="ActionContainer">
                               <div class="SelectCountryContainer">                                   
                                   <autocomplete-wrapper
                                       v-on:selected-country="onSelectedCountry"
                                       :fetching-shipping-info="false">
                                   </autocomplete-wrapper>
                               </div>
                           </div>
                       </div>
                   </modal>
               </div>`,
    methods: {
        onSelectedCountry(country) {
            this.$modal.hide('shipping-countries-select-modal');

            this.$emit('add-filter', country);
        },

        closeModal() {
            this.$modal.hide('shipping-countries-select-modal');
        }
    },
    components: {
        'autocomplete-wrapper': AutocompleteWrapper,
    }
};

export const ShippingCountryFilter = {
    template: `<div class="SingleFilterWrapper">

                  <shipping-countries-modal
                      v-on:add-filter="addFilter">
                  </shipping-countries-modal>
                  
                  <p
                      @click="openModal"
                      class="SingleFilter"
                  >{{filterText}} ... ?<i class="fas fa-plus"></i></p> 
               </div>`,
    props: ['eventName', 'filterText'],
    methods: {
        addFilter(country) {
            this.$emit(this.eventName, country);
        },

        openModal() {
            this.$modal.show('shipping-countries-select-modal')
        }
    },
    components: {
        'shipping-countries-modal': ShippingCountriesModal,
    }
};