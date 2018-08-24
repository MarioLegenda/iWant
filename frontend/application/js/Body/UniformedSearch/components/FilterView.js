import {ButtonFilter} from "./FilterType/ButtonFilter";
import {events} from "../../../events";
import {PriceRange} from "./FilterType/PriceRange";
import {ShipsTo} from "./FilterType/ShipsTo";

export const FilterView = {
    template: `<button-filter 
                    v-if="filterData.type === 'button'"
                    v-bind:filterData="filterData"
                    v-on:search-filter-filter-added="addFilter">
               </button-filter>
               
               <price-range
                    v-else-if="filterData.type === 'PriceRange'"
                    v-bind:filterData="filterData"
                    v-on:price-range-update="priceRangeUpdate">
               </price-range>
               
               <ships-to
                    v-else-if="filterData.type === 'ShipsTo'"
                    v-bind:filterData="filterData"
                    v-on:search-on-ships-to="onShipsTo">
               </ships-to>
`,
    props: ['filterData'],
    components: {
        'button-filter': ButtonFilter,
        'price-range': PriceRange,
        'ships-to': ShipsTo,
    },
    methods: {
        addFilter: function(id) {
            this.$emit(events.FILTER_ADDED, id);
        },
        priceRangeUpdate(priceRange) {
            this.$emit('price-range-update', priceRange);
        },
        onShipsTo(country) {
            this.$emit('search-on-ships-to', country);
        }
    }
};