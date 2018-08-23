import {SearchItem} from "./SearchItem";
import {FilterBox} from "./FilterBox";

export const UniformedSearch = {
    data: function() {
        return {
            keywords: null,
            filters: [],
            errors: {
                badSearchInput: false
            }
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
                    
                    <filter-box></filter-box>

                    <div class="search-listing-wrapper wrap">
                        <div class="search-listing">
                            <search-item></search-item>
                            <search-item></search-item>
                            <search-item></search-item>
                            <search-item></search-item>
                            <search-item></search-item>
                            <search-item></search-item>
                            <search-item></search-item>
                        </div>
                    </div>
               </div>`,
    components: {
        'search-item': SearchItem,
        'filter-box': FilterBox
    },
    computed: {
        hasSearchError: function() {
            if (this.errors.badSearchInput === true) {
                return {
                    'keywords-error': true
                }
            }

            if (this.errors.badSearchInput === false) {
                return {
                    'keywords-error': false
                }
            }
        }
    },
    methods: {
        onKeywordsChange() {
            if (!isString(this.keywords) || this.keywords === '') {
                this.errors.badSearchInput = true;
            } else {
                this.errors.badSearchInput = false;
            }
        },
        onSearch: function() {
            this.errors.badSearchInput = false;
            if (!isString(this.keywords) || this.keywords === '') {
                this.errors.badSearchInput = true;
            } else {
                this.errors.badSearchInput = false;
            }
        }
    }
};