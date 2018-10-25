import {Sentence} from "./Sentence";
import {ShippingCountry} from "../Filters/Choosing/ShippingCountry";
import {Taxonomy} from "../Filters/Choosing/Taxonomy";
import {SingleAddFilter} from "../Filters/Choosing/SingleAddFilter";

export const Filters = {
    data: function() {
        return {
            lowestPrice: false,
            highestPrice: false,
            errors: [],
        }
    },
    template: `<div class="Filters">
                    <input type="hidden" :value="filtersEvent" />
                    <div class="Filters_ChoosingFilters">
                        <h1 class="ChoosingFilters-title">Filter your search results</h1>
                        
                        <p class="Error" v-for="error in errors">{{error}}</p>
                        
                        <div class="GenericFiltersWrapper">
                            <single-add-filter
                                v-on:add-lowest-price="addLowestPrice"
                                event-name="add-lowest-price"
                                filter-text="Lowest price ">
                            </single-add-filter>
                        
                            <single-add-filter
                                v-on:add-highest-price="addHighestPrice"
                                event-name="add-highest-price"
                                filter-text="Highest price ">
                            </single-add-filter>
                        
                            <single-add-filter
                                v-on:add-high-quality="addHighQuality"
                                event-name="add-high-quality"
                                filter-text="High quality ">
                            </single-add-filter>
                        
                            <shipping-country
                                v-on:on-add-shipping-countries="addShippingCountries">
                            </shipping-country>
                        
                            <taxonomy
                                v-on:on-add-taxonomies="addTaxonomies">
                            </taxonomy>
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
        addShippingCountries(shippingCountries) {
            const normalized = shippingCountries.normalize();


        },
        removeShippingCountries() {
        },
        removeTaxonomies() {
        },
        addTaxonomies(taxonomy) {
            const normalized = taxonomy.normalize();
        },
    },
    components: {
        'shipping-country': ShippingCountry,
        'taxonomy': Taxonomy,
        'sentence': Sentence,
        'single-add-filter': SingleAddFilter,
    }
};