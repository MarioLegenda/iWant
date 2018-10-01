import {Sentence} from "./Sentence";
import {LowestPrice} from "./Filters/Choosing/LowestPrice";
import {LowestPriceView} from "./Filters/View/LowestPriceView";
import {HighestPrice} from "./Filters/Choosing/HighestPrice";
import {HighestPriceView} from "./Filters/View/HighestPriceView";
import {HighQuality} from "./Filters/Choosing/HighQuality";
import {HighQualityView} from "./Filters/View/HighQualityView";
import {ShippingCountry} from "./Filters/Choosing/ShippingCountry";
import {ShippingCountriesView} from "./Filters/View/ShippingCountriesView";
import {Marketplace} from "./Filters/Choosing/Marketplace";
import {MarketplaceView} from "./Filters/View/MarketplaceView";
import {Taxonomy} from "./Filters/Choosing/Taxonomy";
import {TaxonomyView} from "./Filters/View/TaxonomyView";

export const Filters = {
    data: function() {
        return {
            lowestPriceView: false,
            highestPriceView: false,
            highQualityView: false,
            shippingCountriesView: false,
            shippingCountries: [],
            marketplaceView: false,
            taxonomyView: false,
            taxonomies: [],
            marketplaces: [],
            errors: [],
        }
    },
    template: `<div class="Filters">

                    <div class="Filters_AddedFilters">
                        <lowest-price-view
                            v-if="lowestPriceView"
                            v-on:remove-lowest-price="removeLowestPrice">
                        </lowest-price-view>
                        
                        <highest-price-view
                            v-if="highestPriceView"
                            v-on:remove-highest-price="removeHighestPrice">
                        </highest-price-view>
                        
                        <high-quality-view
                            v-if="highQualityView"
                            v-on:remove-high-quality="removeHighQuality">
                        </high-quality-view>
                        
                        <shipping-countries-view
                            v-if="shippingCountriesView"
                            v-bind:shippingCountries="shippingCountries"
                            v-on:remove-shipping-countries="removeShippingCountries">
                        </shipping-countries-view>
                        
                        <marketplace-view
                            v-if="marketplaceView"
                            v-bind:marketplaces="marketplaces"
                            v-on:remove-marketplaces="removeMarketplaces">
                        </marketplace-view>
                        
                        <taxonomy-view
                            v-if="taxonomyView"
                            v-bind:taxonomies="taxonomies"
                            v-on:remove-taxonomies="removeTaxonomies">
                        </taxonomy-view>
                    </div>
                    
                    <div class="Filters_ChoosingFilters">
                        <h1 class="ChoosingFilters-title">Filter your search results</h1>
                        
                        <p class="Error" v-for="error in errors">{{error}}</p>
                                                
                        <lowest-price
                            v-on:add-lowest-price="addLowestPrice">
                        </lowest-price>
                        
                        <highest-price
                            v-on:add-highest-price="addHighestPrice">
                        </highest-price>
                        
                        <high-quality
                            v-on:add-high-quality="addHighQuality">
                        </high-quality>
                        
                        <shipping-country
                            v-on:on-add-shipping-countries="addShippingCountries">
                        </shipping-country>
                        
                        <marketplace
                            v-on:on-add-marketplaces="addMarketplaces">
                        </marketplace>
                        
                        <taxonomy
                            v-on:on-add-taxonomies="addTaxonomies">
                        </taxonomy>
                    </div>
                    
               </div>`,
    methods: {
        addLowestPrice() {
            this.errors = [];

            if (this.highestPriceView) {
                this.errors.push('Lowest price filter cannot be used with highest price filter and vice versa');

                return false;
            }

            this.lowestPriceView = true;

            this.$emit('add-filter', {
                name: 'lowestPrice',
                value: this.lowestPriceView
            });
        },
        removeLowestPrice() {
            this.errors = [];

            this.lowestPriceView = false;

            this.$emit('add-filter', {
                name: 'lowestPrice',
                value: this.lowestPriceView
            });
        },
        addHighestPrice() {
            this.errors = [];

            if (this.lowestPriceView) {
                this.errors.push('Lowest price filter cannot be used with highest price filter and vice versa');

                return false;
            }

            this.highestPriceView = true;

            this.$emit('add-filter', {
                name: 'highestPrice',
                value: this.highestPriceView
            });
        },
        removeHighestPrice() {
            this.errors = [];

            this.highestPriceView = false;

            this.$emit('add-filter', {
                name: 'highestPrice',
                value: this.highestPriceView
            });
        },
        addHighQuality() {
            this.errors = [];

            this.highQualityView = true;

            this.$emit('add-filter', {
                name: 'highQuality',
                value: this.highQualityView
            });
        },
        removeHighQuality() {
            this.errors = [];

            this.highQualityView = false;

            this.$emit('add-filter', {
                name: 'highQuality',
                value: this.highQualityView
            });
        },
        addShippingCountries(shippingCountries) {
            const normalized = shippingCountries.normalize();

            if (normalized.length > 0) {
                this.shippingCountriesView = true;
                this.shippingCountries = normalized;
            }

            this.$emit('add-filter', {
                name: 'shippingCountries',
                value: this.shippingCountries
            });
        },
        removeShippingCountries() {
            this.shippingCountriesView = false;
            this.shippingCountries = [];

            this.$emit('add-filter', {
                name: 'shippingCountries',
                value: this.shippingCountries
            });
        },
        addMarketplaces(marketplaces) {
            const normalized = marketplaces.normalize();

            if (normalized.length > 0) {
                this.marketplaceView = true;
                this.marketplaces = normalized;
            }

            this.$emit('add-filter', {
                name: 'marketplaces',
                value: this.marketplaces
            });
        },
        removeMarketplaces() {
            this.marketplaceView = false;
            this.marketplaces = [];

            this.$emit('add-filter', {
                name: 'marketplaces',
                value: this.marketplaces
            });
        },
        removeTaxonomies() {
            this.taxonomyView = false;
            this.taxonomies = [];

            this.$emit('add-filter', {
                name: 'taxonomies',
                value: this.taxonomies
            });
        },
        addTaxonomies(taxonomy) {
            const normalized = taxonomy.normalize();

            if (normalized.length > 0) {
                this.taxonomyView = true;
                this.taxonomies = normalized;
            }

            this.$emit('add-filter', {
                name: 'taxonomies',
                value: this.taxonomies
            });
        },
    },
    components: {
        'lowest-price-view': LowestPriceView,
        'lowest-price': LowestPrice,
        'highest-price-view': HighestPriceView,
        'highest-price': HighestPrice,
        'high-quality': HighQuality,
        'high-quality-view': HighQualityView,
        'shipping-country': ShippingCountry,
        'shipping-countries-view': ShippingCountriesView,
        'marketplace': Marketplace,
        'marketplace-view': MarketplaceView,
        'taxonomy': Taxonomy,
        'taxonomy-view': TaxonomyView,
        'sentence': Sentence,
    }
};