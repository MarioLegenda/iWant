import {Simple} from "./Added/Simple";
import {Select} from "./Added/Select";

export const AddedFiltersFactory = {
    template: `<div>
                   <select-item 
                        v-if="filter.name === 'ShippingCountry'" 
                        v-on:remove-simple-filter="onRemoveSimpleFilter" 
                        v-bind:data="filter">
                   </select-item>
                   <simple 
                        v-if="filter.type === 'simple'" 
                        v-on:remove-simple-filter="onRemoveSimpleFilter" 
                        v-bind:data="filter">
                   </simple>
               </div>`,
    props: ['filter'],
    methods: {
        onRemoveSimpleFilter(data) {
            this.$emit('remove-simple-filter', data);
        }
    },
    components: {
        'simple': Simple,
        'select-item': Select,
    }
};