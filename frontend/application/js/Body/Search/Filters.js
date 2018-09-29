import {Sentence} from "./Sentence";
import {LowestPrice} from "./Filters/Choosing/LowestPrice";
import {LowestPriceView} from "./Filters/View/LowestPriceView";
import {HighestPrice} from "./Filters/Choosing/HighestPrice";
import {HighestPriceView} from "./Filters/View/HighestPriceView";
import {HighQuality} from "./Filters/Choosing/HighQuality";
import {HighQualityView} from "./Filters/View/HighQualityView";

export const Filters = {
    data: function() {
        return {
            lowestPriceView: false,
            highestPriceView: false,
            highQualityView: false,
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
        },
        removeLowestPrice() {
            this.errors = [];

            this.lowestPriceView = false;
        },
        addHighestPrice() {
            this.errors = [];

            if (this.lowestPriceView) {
                this.errors.push('Lowest price filter cannot be used with highest price filter and vice versa');

                return false;
            }

            this.highestPriceView = true;
        },
        removeHighestPrice() {
            this.errors = [];

            this.highestPriceView = false;
        },
        addHighQuality() {
            this.highQualityView = true;
        },
        removeHighQuality() {
            this.highQualityView = false;
        }
    },
    components: {
        'lowest-price-view': LowestPriceView,
        'lowest-price': LowestPrice,
        'highest-price-view': HighestPriceView,
        'highest-price': HighestPrice,
        'high-quality': HighQuality,
        'high-quality-view': HighQualityView,
        'sentence': Sentence,
    }
};