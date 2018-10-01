import {SearchBoxAdvanced} from "./SearchBoxAdvanced";
import {Filters} from "./Filters";
import {Sentence} from "./Sentence";

export const AdvancedSearch = {
    data: function() {
        return {
            filters: {
                lowestPrice: true,
                highestPrice: false,
                highQuality: false,
                shippingCountries: [],
                marketplaces: [],
                taxonomies: [],
            }
        }
    },
    template: `<div class="AdvancedSearch">                    
                    <search-box-advanced v-on:submit="submit"></search-box-advanced>
                    
                    <sentence v-bind:filters="filters"></sentence>
                    
                    <filters v-on:add-filter="addFilter"></filters>
               </div>`,
    methods: {
        addFilter(filter) {
            if (!this.filters.hasOwnProperty(filter.name)) {
                throw new Error(`Filter ${filter.name} not found`);
            }

            this.filters[filter.name] = filter.value;
        },
        submit() {
            console.log(this.filters);
        }
    },
    components: {
        'search-box-advanced': SearchBoxAdvanced,
        'filters': Filters,
        'sentence': Sentence,
    }
};