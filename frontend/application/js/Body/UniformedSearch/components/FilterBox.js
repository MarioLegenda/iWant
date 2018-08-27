import {FilterView} from "./FilterView";
import {AddedFiltersView} from "./AddedFiltersView";

import {
    HIGHEST_PRICE,
    LOWEST_PRICE,
    PRICE_RANGE,
    SHIPS_TO,
    BEST_QUALITY,
    USED,
    HANDMADE
} from "../constants";

import {Processor} from "../filtersProcessor";
import {ToggleFilterButton} from "./ToggleFilterButton";

export const FilterBox = {
    filtersProcessor: null,
    data: function() {
        return {
            showFilterBoxProp: false,
            customErrors: {
                lowestHighestPrice: false,
                minPrice: false,
                maxPrice: false,
                priceRange: false,
            },
            addedFilters: [],
            filtersView: [
                {
                    id: 1,
                    active: true,
                    type: 'button',
                    filterType: LOWEST_PRICE,
                    position: 1,
                    text: 'Lowest price',
                    data: null
                },
                {
                    id: 2,
                    active: true,
                    type: 'button',
                    filterType: HIGHEST_PRICE,
                    position: 2,
                    text: 'Highest price',
                    data: null
                },
                {
                    id: 3,
                    active: true,
                    filterType: PRICE_RANGE,
                    type: PRICE_RANGE,
                    position: 3,
                    text: '',
                    data: null,
                },
                {
                    id: 4,
                    active: true,
                    type: 'button',
                    filterType: BEST_QUALITY,
                    position: 4,
                    text: 'High quality',
                    data: null
                },
                {
                    id: 5,
                    active: true,
                    filterType: SHIPS_TO,
                    type: SHIPS_TO,
                    position: 6,
                    text: '',
                    data: null
                },
                {
                    id: 6,
                    active: true,
                    type: 'button',
                    text: 'Handmade',
                    filterType: HANDMADE,
                    position: 7,
                    data: null
                },
                {
                    id: 7,
                    active: true,
                    filterType: USED,
                    type: 'button',
                    position: 5,
                    text: 'Used',
                    data: null
                }
            ]
        }
    },
    computed: {
        toggleAnimation: function() {
            return {
                'animated fadeIn': this.showFilterBoxProp === true,
                'animated fadeOut': this.showFilterBoxProp === false,
            }
        },
        sortedViewFilters: function() {
            let positions = [];
            let entries = [];

            for (const entry of this.filtersView) {
                positions.push(entry.position);
            }

            positions.sort(function(a, b) {
                return a - b;
            });

            for (const position of positions) {
                entries.push(this.filtersView.filter(e => e.position === position)[0]);
            }

            return entries;
        },
        addedFiltersChange: function() {
            console.log(this.addedFilters);
            this.$emit('on-filter-add', this.addedFilters);
        },
    },
    created() {
        this.filtersProcessor = new Processor(
            this.filtersView,
            this.addedFilters,
            this.customErrors
        );
    },
    template: `<div class="filter-box-wrapper wrap">
                   <transition name="fade">
                        <div v-if="showFilterBoxProp" v-bind:class="toggleAnimation" class="filter-box-fixed-width-row">
                            <template>
                                <div class="added-filters-box wrap">
                                    <added-filters-view
                                        v-bind:filters="addedFilters"
                                        v-on:search-filter-filter-remove="removeFilter">
                                    </added-filters-view>
                                    
                                    <span class="filter-message">*If no filters are present, the search is done by lowest price</span>
                                </div>
                                
                                <div class="add-filters-box">              
                                    <span v-if="customErrors.lowestHighestPrice" class="error-message wrap"><i class="info fas fa-info-circle"></i>{{customErrors.lowestHighestPrice}}<i class="close fas fa-times" v-on:click="closeErrorMessage($event, 'lowestHighestPrice')"></i></span>
                                    <span v-if="customErrors.minPrice" class="error-message wrap"><i class="info fas fa-info-circle"></i>{{customErrors.minPrice}}<i class="close fas fa-times" v-on:click="closeErrorMessage($event, 'minPrice')"></i></span>
                                    <span v-if="customErrors.maxPrice" class="error-message wrap"><i class="info fas fa-info-circle"></i>{{customErrors.maxPrice}}<i class="close fas fa-times" v-on:click="closeErrorMessage($event, 'maxPrice')"></i></span>
                                    <span v-if="customErrors.priceRange" class="error-message wrap"><i class="info fas fa-info-circle"></i>{{customErrors.priceRange}}<i class="close fas fa-times" v-on:click="closeErrorMessage($event, 'priceRange')"></i></span>
                                    
                                    <filter-view
                                        v-for="(filter, index) in sortedViewFilters"  
                                        :key="index"
                                        v-bind:filterData="filter"
                                        v-on:on-lowest-highest-price="addGenericFilter"
                                        v-on:price-range-update="priceRangeUpdate"
                                        v-on:search-on-ships-to="onShipsTo">
                                    </filter-view>
                                </div>
                                
                            </template>
                            
                        </div>
                   </transition>
                        
                   <transition name="moveDown">
                        <toggle-filter-box-button v-on:toggle-filter-box="showFilterBox"></toggle-filter-box-button>
                   </transition>
               </div>`,
    methods: {
        addGenericFilter: function(id) {
            this.filtersProcessor.errors.reset();

            if (this.filtersProcessor.addGenericFilter(id)) {
                this.deactivateFilter(id);
            }

            if (!this.filtersProcessor.errors.hasErrors()) {
                this.filtersProcessor.errors.reset();

                this.$emit('on-filter-add', this.addedFilters);

                return false;
            }
        },

        onShipsTo(country) {
            this.filtersProcessor.upsertShipsToCountry(5, country);

            this.$emit('on-filter-add', this.addedFilters);

            this.activateFilter(5);
        },

        priceRangeUpdate: function(priceRange) {
            const ranges = ['minPrice', 'maxPrice'];
            let hasErrors = false;

            for (const range of ranges) {
                const message = (range === 'minPrice') ? 'Minimum price' : 'Maximum price';

                const r = priceRange[range];
                if (isNaN(parseInteger(r)) && r !== null) {
                    this.filtersProcessor.errors.addError(range, `${message} has to be a number`);
                    hasErrors = true;
                } else {
                    const parsedR = parseInteger(priceRange[range]);

                    priceRange[range] = (!isNaN(parsedR)) ? parsedR : null;
                    this.filtersProcessor.errors.addError(range, false);
                }
            }

            if (hasErrors) {
                return false;
            }

            const minPrice = priceRange.minPrice;
            const maxPrice = priceRange.maxPrice;

            if (Number.isInteger(minPrice) && Number.isInteger(maxPrice)) {
                if (minPrice > maxPrice) {
                    this.filtersProcessor.upsertRangeFilter(priceRange.id,
                        Object.assign({}, priceRange, {maxPrice: null})
                    );

                    this.filtersProcessor.errors.addError('priceRange', 'Maximum price has to be greater that the minimum price');

                    return false;
                } else {
                    this.filtersProcessor.errors.addError('priceRange', false);
                }
            } else {
                this.filtersProcessor.errors.addError('priceRange', false);
            }

            this.filtersProcessor.upsertRangeFilter(priceRange.id, priceRange);
            this.deactivateFilter(priceRange.id);

            this.$emit('on-filter-add', this.addedFilters);
        },

        removeFilter: function(id) {
            if(this.filtersProcessor.removeFilter(id)) {
                this.activateFilter(id);

                this.$emit('on-filter-add', this.addedFilters);
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
        },

        closeErrorMessage($event, propName) {
            this.filtersProcessor.errors.removeError(propName);
        },

        showFilterBox: function(showFilter) {
            this.showFilterBoxProp = showFilter;
        },
    },
    components: {
        'filter-view': FilterView,
        'added-filters-view': AddedFiltersView,
        'toggle-filter-box-button': ToggleFilterButton,
    }
};