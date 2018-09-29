import {SearchBoxAdvanced} from "./SearchBoxAdvanced";
import {Filters} from "./Filters";
import {Sentence} from "./Sentence";

export const AdvancedSearch = {
    data: function() {
        return {
            filters: []
        }
    },
    template: `<div class="AdvancedSearch">                    
                    <search-box-advanced></search-box-advanced>
                    
                    <sentence v-bind:filters="filters"></sentence>
                    
                    <filters v-on:add-filter="addFilter"></filters>
               </div>`,
    methods: {
        addFilter(filter) {
            this.filters.push(filter);
        }
    },
    components: {
        'search-box-advanced': SearchBoxAdvanced,
        'filters': Filters,
        'sentence': Sentence,
    }
};