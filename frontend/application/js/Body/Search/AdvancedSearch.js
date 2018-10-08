import {SearchBoxAdvanced} from "./SearchBoxAdvanced";
import {Filters} from "./Filters";
import {Sentence} from "./Sentence";
import {RepositoryFactory} from "../../services/repositoryFactory";

export const AdvancedSearch = {
    data: function() {
        return {
            showSentence: false,
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
                    <search-box-advanced 
                        v-on:submit="submit"
                        v-on:on-search-term-change="onSearchTermChange">
                    </search-box-advanced>

                    <sentence
                        v-bind:sentenceData="sentenceData"
                        v-bind:showSentence="showSentence">
                    </sentence>
                    
                    <filters v-on:add-filter="addFilter"></filters>
               </div>`,
    computed: {
        sentenceData: function() {
            return {
                filters: this.filters,
                keyword: this.keyword,
            }
        }
    },
    methods: {
        addFilter(filter) {
            this.showSentence = true;

            if (!this.filters.hasOwnProperty(filter.name)) {
                throw new Error(`Filter ${filter.name} not found`);
            }

            this.filters[filter.name] = filter.value;
        },
        onSearchTermChange(searchTerm) {
            this.showSentence = true;

            this.keyword = searchTerm;
        },
        submit(keyword) {
            this.keyword = keyword;

            const searchRepo = RepositoryFactory.create('search');

            searchRepo.getSearch(this.createModel(), function(data) {
                console.log(data);
            });
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