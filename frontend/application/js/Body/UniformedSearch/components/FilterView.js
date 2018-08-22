import {ButtonFilter} from "./FilterType/ButtonFilter";
import {events} from "../../../events";

export const FilterView = {
    template: `<button-filter 
                    v-if="filterData.type === 'button'"
                    v-bind:filterData="filterData"
                    v-on:search-filter-filter-added="addFilter"
               >
               </button-filter>`,
    props: ['filterData'],
    components: {
        'button-filter': ButtonFilter
    },
    methods: {
        addFilter: function(id) {
            this.$emit(events.FILTER_ADDED, id);
        }
    }
};