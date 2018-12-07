import {SingleAddFilter} from "../Filters/Choosing/SingleAddFilter";
import {SAVED_STATE_MODE} from "../../../store/constants";

export const Filters = {
    data: function() {
        return {
            responsiveMenuOpened: true,
            lowestPrice: false,
            highestPrice: false,
            highQuality: false,
            fixedPrice: false,
            errors: [],
        }
    },

    created() {
        if (this.getCurrentSearchStateMode === SAVED_STATE_MODE) {
            const filtersEvent = this.$store.state.filtersEvent;

            const filters = ['lowestPrice', 'highestPrice', 'highQuality', 'fixedPrice'];

            for (const f of filters) {
                if (filtersEvent[f] === true) {
                    this[f] = filtersEvent[f];
                }
            }
        }

        this.$store.subscribe((mutation, state) => {
            if (mutation.type === 'filtersEvent') {
                if (!state.filtersEvent.lowestPrice) {
                    this.removeLowestPrice();
                }

                if (!state.filtersEvent.highestPrice) {
                    this.removeHighestPrice();
                }

                if (!state.filtersEvent.highQuality) {
                    this.removeHighQuality();
                }

                if (!state.filtersEvent.fixedPrice) {
                    this.removeFixedPrice();
                }
            }
        });
    },

    template: `<div class="RightPanel FiltersWrapper">
                    
                    <i class="FilterMenuOpener fas fa-bars" @click="openResponsiveMenu(...arguments)"></i>
                    
                    <div class="FiltersList">
                        <h1 class="Title">{{translationsMap.filters.title}}</h1>
                        
                        <p class="Error" v-for="error in errors">{{error}}</p>
                        
                        <div class="GenericFiltersWrapper">
                                                    
                            <h1 class="OrganisedFiltersSeparator">Price</h1>
                            
                            <single-add-filter
                                v-on:add-lowest-price="addLowestPrice"
                                event-name="add-lowest-price"
                                :filter-text="translationsMap.filters.lowestPriceFilter">
                            </single-add-filter>
                        
                            <single-add-filter
                                v-on:add-highest-price="addHighestPrice"
                                event-name="add-highest-price"
                                :filter-text="translationsMap.filters.highestPriceFilter">
                            </single-add-filter>
                            
                            <single-add-filter
                                v-on:add-fixed-price="addFixedPrice"
                                event-name="add-fixed-price"
                                :filter-text="translationsMap.filters.fixedPriceFilter">
                            </single-add-filter>
                        
                            <h1 class="OrganisedFiltersSeparator">Quality</h1>

                            <single-add-filter
                                v-on:add-high-quality="addHighQuality"
                                event-name="add-high-quality"
                                :filter-text="translationsMap.filters.highQualityFilter">
                            </single-add-filter>
                        </div>
                    </div>
                    
               </div>`,

    computed: {
        translationsMap: function() {
            return this.$store.state.translationsMap;
        },

        getCurrentSearchStateMode: function() {
            return this.$store.getters.getCurrentSearchStateMode;
        }
    },

    methods: {
        openResponsiveMenu(event) {
            this.responsiveMenuOpened = !this.responsiveMenuOpened;

            if (this.responsiveMenuOpened) {
                event.target.parentNode.classList.remove('ClickableFilterMenuOpener');
            } else {
                event.target.parentNode.classList.add('ClickableFilterMenuOpener');
            }
        },

        addFixedPrice() {
            this.errors = [];

            this.fixedPrice = true;

            this.$store.commit('filtersEvent', {
                fixedPrice: true,
            });
        },

        addLowestPrice() {
            this.errors = [];

            if (this.highestPrice) {
                this.errors.push('Lowest price filter cannot be used with highest price filter and vice versa');

                return false;
            }

            if (this.lowestPrice) {
                return;
            }

            this.lowestPrice = true;

            this.$store.commit('filtersEvent', {
                lowestPrice: true,
            });
        },

        removeLowestPrice() {
            this.errors = [];

            this.lowestPrice = false;

        },

        addHighestPrice() {
            this.errors = [];

            if (this.lowestPrice) {
                this.errors.push('Lowest price filter cannot be used with highest price filter and vice versa');

                return false;
            }

            if (this.highestPrice) {
                return;
            }

            this.highestPrice = true;

            this.$store.commit('filtersEvent', {
                highestPrice: true,
            });
        },

        removeHighestPrice() {
            this.errors = [];

            this.highestPrice = false;
        },

        addHighQuality() {
            this.errors = [];

            if (this.highQuality) {
                return;
            }

            this.highQuality = true;

            this.$store.commit('filtersEvent', {
                highQuality: true,
            });
        },

        removeHighQuality() {
            this.errors = [];

            this.highQuality = false;
        },

        removeFixedPrice() {
            this.errors = [];

            this.fixedPrice = false;
        }
    },

    components: {
        'single-add-filter': SingleAddFilter,
    }
};