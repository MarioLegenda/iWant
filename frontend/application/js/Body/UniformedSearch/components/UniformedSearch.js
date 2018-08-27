import {Item} from "../../Listing/components/Item";
import {FilterBox} from "./FilterBox";
import {Errors} from "../error";
import {SearchData} from "../searchData";
import {SearchProvider} from "../../Provider/SearchProvider";
import {UniformedSearchList} from "../../Listing/components/UniformedSearchList";

export const UniformedSearch = {
    data: function() {
        return {
            keywords: null,
            filters: [],
            errors: new Errors({
                badSearchInput: false
            }),
        }
    },
    template: `<div id="uniformed_search">
                    <div class="search-box-wrapper wrap">
                        <div class="search-box">
                            <input
                                type="text"
                                placeholder="I would like..."
                                id="keywords_input_search"
                                class="search-input"
                                v-bind:class="hasSearchError"
                                v-model="keywords"
                                v-on:input="onKeywordsChange"
                            />
                            
                            <button @click="onSearch"><i class="fas fa-search"></i></button>
                        
                            <p>* By clicking <i class="fas fa-search"></i> you will search products from Ebay, Amazon, Etsy and Bonanza but more marketplaces will be integrated in the future. See the <router-link to="/promise">Promise</router-link> we give to you to find out more</p>
                        </div>
                    </div>
                    
                    <filter-box v-on:on-filter-add="addFilters"></filter-box>
                    
                    <uniformed-search-list></uniformed-search-list>
               </div>`,
    components: {
        'search-item': Item,
        'filter-box': FilterBox,
        'uniformed-search-list': UniformedSearchList,
    },
    computed: {
        hasSearchError: function() {
            if (this.errors.hasError('badSearchInput')) {
                return {'keywords-error': true}
            }

            if (!this.errors.hasError('badSearchInput')) {
                return {'keywords-error': false}
            }
        }
    },
    methods: {
        addFilters(filters) {
            this.filters = filters;
        },
        onKeywordsChange() {
            if (!isString(this.keywords) || this.keywords === '') {
                this.errors.addError('badSearchInput', true);
            } else {
                this.errors.addError('badSearchInput', false);
            }
        },
        onSearch: function() {
            this.errors.addError('badSearchInput', false);

            if (!isString(this.keywords) || this.keywords === '') {
                this.errors.addError('badSearchInput', true);
            } else {
                this.errors.addError('badSearchInput', false);

                const searchData = new SearchData(
                    this.keywords,
                    this.filters
                );

                const provider = new SearchProvider(searchData);

                provider.provide(this.$store);
            }
        }
    }
};