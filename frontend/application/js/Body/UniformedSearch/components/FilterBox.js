import {FilterView} from "./FilterView";
import {AddedFiltersView} from "./AddedFiltersView";

import {HIGHEST_PRICE, LOWEST_PRICE, PRICE_RANGE} from "../constants";
import {Processor} from "../filtersProcessor";

export const FilterBox = {
    filtersProcessor: null,
    data: function() {
        return {
            errors: [],
            customErrors: {
                minPrice: false,
                maxPrice: false,
                priceRange: false,
            },
            showFilters: false,
            addedFilters: [],
            filtersView: [
                {
                    'id': 1,
                    'active': true,
                    'type': 'button',
                    'filterType': LOWEST_PRICE,
                    'text': 'Lowest price',
                },
                {
                    'id': 2,
                    'active': true,
                    'type': 'button',
                    'filterType': HIGHEST_PRICE,
                    'text': 'Highest price',
                },
                {
                    'id': 3,
                    'active': true,
                    'type': PRICE_RANGE,
                    'values': {},
                }
            ]
        }
    },
    created() {
        this.filtersProcessor = new Processor(this.filtersView, this.addedFilters);
    },
    template: `<div class="filter-box-wrapper wrap">
                        <div class="filter-box-fixed-width-row">
                            <template v-if="showFilters">
                            
                                <div class="added-filters-box wrap">
                                    <added-filters-view 
                                        v-bind:filters="addedFilters"
                                        v-on:search-filter-filter-remove="removeFilter">
                                    </added-filters-view>
                                </div>
                                
                                <div class="add-filters-box">
                                    <filter-view
                                        v-for="(filter, index) in filtersView"  
                                        :key="index"
                                        v-bind:filterData="filter"
                                        v-on:search-filter-filter-added="addFilter"
                                        v-on:price-range-update="priceRangeUpdate">
                                    </filter-view>
                                    
                                    <span v-for="error in errors" class="error-message wrap">{{error}}</span>
                                    
                                    <span v-if="customErrors.minPrice" class="error-message wrap">{{customErrors.minPrice}}</span>
                                    <span v-if="customErrors.maxPrice" class="error-message wrap">{{customErrors.maxPrice}}</span>
                                    <span v-if="customErrors.priceRange" class="error-message wrap">{{customErrors.priceRange}}</span>
                                </div>
                                
                            </template>
                            
                            <div class="wrap">
                                <div class="filter-add-button-wrapper wrap">
                                    <button v-on:click="showFilters = !showFilters">
                                        Add filters
                                        <i v-bind:class="toggleFilterClass"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
               </div>`,
    methods: {
        addFilter: function(id) {
            this.errors = [];

            if (this.filtersProcessor.addFilter(id)) {
                this.deactivateFilter(id);
            }

            if (this.filtersProcessor.errors.length !== 0) {
                this.errors = this.filtersProcessor.errors;
                this.filtersProcessor.resetValidation();
            }
        },

        priceRangeUpdate: function(priceRange) {
            const ranges = ['minPrice', 'maxPrice'];
            let hasErrors = false;

            for (const range of ranges) {
                const message = (range === 'minPrice') ? 'Minimum price' : 'Maximum price';

                const r = priceRange[range];
                if (isNaN(parseInteger(r)) && r !== null) {
                    this.customErrors[range] = `${message} has to be a number`;
                    hasErrors = true;
                } else {
                    priceRange[range] = parseInteger(priceRange[range]);
                    this.customErrors[range] = false;
                }
            }

            if (hasErrors) {
                return false;
            }

            const minPrice = priceRange.minPrice;
            const maxPrice = priceRange.maxPrice;

            if (Number.isInteger(minPrice) && Number.isInteger(maxPrice)) {
                if (minPrice > maxPrice) {
                    this.customErrors.priceRange = 'Maximum price has to be greater that the minimum price';

                    return false;
                } else {
                    this.customErrors.priceRange = false;
                }
            }

            this.filtersProcessor.addRangeFilter(priceRange.id, priceRange);
        },

        removeFilter: function(id) {
            this.errors = [];
            this.filtersProcessor.resetValidation();

            if(this.filtersProcessor.removeFilter(id)) {
                this.activateFilter(id);
            }
        },

        activateFilter(id) {
            this.filtersView.filter((entry, index) => {
                if (entry.id === id) {
                    entry.active = true;
                    this.$set(this.filtersView, index, entry);
                }
            });
        },

        deactivateFilter(id) {
            this.filtersView.filter((entry, index) => {
                if (entry.id === id) {
                    entry.active = false;
                    this.$set(this.filtersView, index, entry);
                }
            });
        }
    },
    computed: {
        toggleFilterClass: function () {
            return {
                'fas fa-angle-right': this.showFilters === false,
                'fas fa-angle-up': this.showFilters === true
            }
        }
    },
    components: {
        'filter-view': FilterView,
        'added-filters-view': AddedFiltersView
    }
};