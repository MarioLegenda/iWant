import {AddedFilter} from "./FilterType/AddedFilter";
import {events} from "../../../events";

export const AddedFiltersView = {
    template: `<div>
                   <div class="added-filters">
                       <added-filter
                           v-for="(filter, index) in filters" 
                           :key="index"
                           v-bind:filter="filter"
                           v-on:search-filter-filter-remove="removeFilter"> 
                       </added-filter>
                   </div>
               </div>`,
    props: ['filters'],
    methods: {
        removeFilter: function(id) {
            this.$emit(events.FILTER_REMOVE, id);
        }
    },
    components: {
        'added-filter': AddedFilter
    }
};