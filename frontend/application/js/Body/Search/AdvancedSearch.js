import {SearchBoxAdvanced} from "./SearchBoxAdvanced";
import {Filters} from "./Filters";
import {Sentence} from "./Sentence";
import {RepositoryFactory} from "../../services/repositoryFactory";

export const AdvancedSearch = {
    data: function() {
        return {
            keyword: null,
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
        submit(keyword) {
            this.keyword = keyword;

            const searchRepo = RepositoryFactory.create('search');

            searchRepo.getSearch(this.createModel(), function(data) {
                console.log(data);
            })
        },
        createModel() {
            return {
                keyword: this.keyword,
                filters: this.filters,
                pagination: {
                    limit: 8,
                    page: 1,
                }
            }
        }
    },
    components: {
        'search-box-advanced': SearchBoxAdvanced,
        'filters': Filters,
        'sentence': Sentence,
    }
};