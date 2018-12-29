export const ShippingDetails = {
    data: function() {
        return {
            countries: null,
            selected: null
        }
    },
    props: ['itemId'],
    template: `<div class="ShippingDetailsWrapper">
                   <modal name="shipping-details-modal">
                       <div class="ShippingDetailsImplementation">
                           <div class="ActionContainer">
                               <div class="SelectCountryContainer">
                                   <span class="TextArea">Select a country to ship to: </span>
                                   <select @change="onCountryChange" v-model="selected" class="SelectArea">
                                       <option 
                                           v-for="item in countries" 
                                           :value="item.alpha2Code" 
                                           :key="item.id">{{item.name}}
                                       </option>
                                   </select>
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
            this.selected = this.countries[0].alpha2Code;
        });
    },

    methods: {
        onCountryChange() {
            this.$repository.SingleItemRepository.getShippingCosts({
                itemId: this.itemId,
                locale: this.$localeInfo.locale,
                destinationCountryCode: this.selected
            }, (r) => {
                console.log(r);
            });
        }
    },
};