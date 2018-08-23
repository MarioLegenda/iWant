import {ButtonFilter} from "./FilterType/ButtonFilter";
import {events} from "../../../events";
import {PriceRange} from "./FilterType/PriceRange";

export const FilterView = {
    template: `<button-filter 
                    v-if="filterData.type === 'button'"
                    v-bind:filterData="filterData"
                    v-on:search-filter-filter-added="addFilter"
               >
               </button-filter>
               <price-range
                    v-else-if="filterData.type === 'PriceRange'"
                    v-bind:filterData="filterData"
                    v-on:search-filter-filter-added="addFilter">
               </price-range>
`,
    props: ['filterData'],
    components: {
        'button-filter': ButtonFilter,
        'price-range': PriceRange
    },
    methods: {
        addFilter: function(id) {
            this.$emit(events.FILTER_ADDED, id);
        }
    }
};