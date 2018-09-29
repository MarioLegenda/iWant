import {Simple} from "./Choosing/Simple";
import {CountrySelect} from "./Choosing/CountrySelect";

export const ChoosingFilterFactory = {
    data: function() {
        return {
            countries: [],
        }
    },
    template: `<div>
                   <country-select-item v-if="filter.name === 'ShippingCountry'" v-bind:data="filter" v-on:on-country-select="onCountrySelect"></country-select-item>
                   <simple v-if="filter.type === 'simple'" v-on:add-simple-filter="onAddSimpleFilter" v-bind:data="filter"></simple>
               </div>`,
    props: ['filter'],
    methods: {
        onAddSimpleFilter(data) {
            this.$emit('add-simple-filter', data);
        },
        onCountrySelect(data) {
            this.$emit('on-country-select', data);
        },
    },
    components: {
        'simple': Simple,
        'country-select-item': CountrySelect,
    }
};