import {ChoosingFilterFactory} from "./Filters/ChoosingFilterFactory";
import {AddedFiltersFactory} from "./Filters/AddedFiltersFactory";
import {Sentence} from "./Sentence";

class FilterValidation {
    constructor(filters) {
        this.filters = filters;
        this.errors = [];
        this.messages = {
            highLowFilter: 'Lowest price filter cannot be used with Highest price filter and vice versa'
        }
    }

    alreadyAdded(data) {
        for (let filter of this.filters) {
            if (filter.id === data.id) {
                return true;
            }
        }

        return false;
    }

    lowHighFilterValidation(filterToInclude) {
        const mappedIds = [1, 2];
        let hasId = false;

        for (let filter of this.filters) {
            if (mappedIds.includes(filter.id)) {
                hasId = true;

                break;
            }
        }

        if (hasId === true) {
            if (mappedIds.includes(filterToInclude.id)) {
                this.errors.push(this.messages.highLowFilter);
            }
        }
    }
}

class FilterTraversal {
    hasFilterById(filter, filters) {
        for (let f of filters) {
            if (f.id === filter.id) {
                return true;
            }
        }

        return false;
    }
}

export const Filters = {
    data: function() {
        return {
            filterValidation: null,
            filterTraversal: null,
            errors: [],
            choosingFilters: [
                {
                    id: 1,
                    type: 'simple',
                    name: 'LowestPrice',
                    text: 'Lowest price',
                },
                {
                    id: 2,
                    type: 'simple',
                    name: 'HighestPrice',
                    text: 'Highest price',
                },
                {
                    id: 3,
                    type: 'simple',
                    name: 'HighQuality',
                    text: 'High quality',
                },
                {
                    id: 4,
                    type: 'select',
                    name: 'ShippingCountry',
                    text: 'Add shipping country',
                    values: [],
                }
            ],
            addedFilters: []
        }
    },
    created() {
        this.filterValidation = new FilterValidation(this.addedFilters);
        this.filterTraversal = new FilterTraversal();
    },
    template: `<div class="Filters">

                    <div class="Filters_AddedFilters">
                        <added-filter-factory
                            v-for="(value, index) in addedFilters"
                            :key="index"
                            v-bind:filter="value"
                            v-on:remove-simple-filter="onRemoveSimpleFilter">
                        </added-filter-factory>
                    </div>
                    
                    <div class="Filters_ChoosingFilters">
                        <h1 class="ChoosingFilters-title">Filter your search results</h1>
                        
                        <p class="Error" v-for="error in errors">{{error}}</p>
                        
                        <choosing-filter-factory
                            v-for="(value, index) in choosingFilters"
                            :key="index"
                            v-bind:filter="value"
                            v-on:add-simple-filter="onAddSimpleFilter"
                            v-on:on-country-select="onCountrySelect">
                        </choosing-filter-factory>
                    </div>
                    
               </div>`,
    methods: {
        onCountrySelect(data) {
            const countries = data.countries;
            const filter = data.filter;

            if (countries === null) {
                return false;
            }

            if (!this.filterTraversal.hasFilterById(filter, this.addedFilters)) {
                this.addedFilters.push(filter);
            }

            let foundFilter = null;
            for (let i = 0; i < this.addedFilters.length; i++) {
                if (filter.id === this.addedFilters[i].id) {
                    foundFilter = {
                        index: i,
                        filter: this.addedFilters[i],
                    };

                    break;
                }
            }

            if (foundFilter === null) {
                return false;
            }

            let newFilter = Object.assign({}, foundFilter.filter);

            if (countries === 'worldwide') {
                newFilter.values.push('worldwide');
            }

            if (Array.isArray(countries)) {
                for (let c of countries) {
                    newFilter.values.push(c);
                }
            }

            this.addedFilters.splice(foundFilter.index, 1);

            this.addedFilters.push(newFilter);
        },

        onAddSimpleFilter(data) {
            this.errors = [];
            this.filterValidation.errors = [];

            if (this.filterValidation.alreadyAdded(data)) {
                return;
            }

            this.filterValidation.lowHighFilterValidation(data);

            this.errors = this.filterValidation.errors;

            if (this.errors.length > 0) {
                return;
            }

            this.addedFilters.push(data);

            this.$emit('add-filter', data);
        },

        onRemoveSimpleFilter(data) {
            this.errors = [];

            for (let i = 0; this.addedFilters.length; i++) {
                let addedFilter = this.addedFilters[i];

                if (addedFilter.id === data.id) {
                    this.addedFilters.splice(i, 1);

                    break;
                }
            }
        }
    },
    components: {
        'choosing-filter-factory': ChoosingFilterFactory,
        'added-filter-factory': AddedFiltersFactory,
        'sentence': Sentence,
    }
};