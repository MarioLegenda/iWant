import {Sentence} from "./Sentence";
import {SingleAddFilter} from "../Filters/Choosing/SingleAddFilter";

export const Filters = {
    data: function() {
        return {
            lowestPrice: false,
            highestPrice: false,
            errors: [],
        }
    },
    template: `<div class="RightPanel FiltersWrapper">
                    <input type="hidden" :value="filtersEvent" />
                    
                    <div class="FiltersList">
                        <h1 class="Title">{{translationsMap.filterHeader}}</h1>
                        
                        <p class="Error" v-for="error in errors">{{error}}</p>
                        
                        <div class="GenericFiltersWrapper">
                            <single-add-filter
                                v-on:add-lowest-price="addLowestPrice"
                                event-name="add-lowest-price"
                                :filter-text="translationsMap.lowestPriceFilter">
                            </single-add-filter>
                        
                            <single-add-filter
                                v-on:add-highest-price="addHighestPrice"
                                event-name="add-highest-price"
                                :filter-text="translationsMap.highestPriceFilter">
                            </single-add-filter>
                        
                            <single-add-filter
                                v-on:add-high-quality="addHighQuality"
                                event-name="add-high-quality"
                                :filter-text="translationsMap.highQualityFilter">
                            </single-add-filter>
                        </div>
                    </div>
                    
               </div>`,
    computed: {
        filtersEvent: function() {
            const filtersEvent = this.$store.state.filtersEvent;

            this.errors = [];

            this.highestPrice = filtersEvent.highestPrice;
            this.lowestPrice = filtersEvent.lowestPrice;

            return filtersEvent;
        },
        translationsMap: function() {
            return this.$store.state.translationsMap;
        }
    },
    methods: {
        addLowestPrice() {
            this.errors = [];

            if (this.highestPrice) {
                this.errors.push('Lowest price filter cannot be used with highest price filter and vice versa');

                return false;
            }

            this.highestPrice = true;

            this.$store.commit('filtersEvent', {
                lowestPrice: true,
            });
        },
        removeLowestPrice() {
            this.errors = [];

            this.lowestPrice = false;

            this.$store.commit('filtersEvent', {
                lowestPrice: false,
            })
        },
        addHighestPrice() {
            this.errors = [];

            if (this.lowestPrice) {
                this.errors.push('Lowest price filter cannot be used with highest price filter and vice versa');

                return false;
            }

            this.$store.commit('filtersEvent', {
                highestPrice: true,
            });
        },
        removeHighestPrice() {
            this.errors = [];

            this.$store.commit('filtersEvent', {
                lowestPrice: false,
            });
        },
        addHighQuality() {
            this.errors = [];

            this.$store.commit('filtersEvent', {
                highQuality: true,
            });
        },
        removeHighQuality() {
            this.errors = [];

            this.$store.commit('filtersEvent', {
                highQuality: false,
            });
        },
    },
    components: {
        'sentence': Sentence,
        'single-add-filter': SingleAddFilter,
    }
};