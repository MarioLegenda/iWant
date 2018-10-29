import {Item} from "../../../Listing/components/Item";

export const Price = {
    template: `
        <p v-if="currency === 'USD'" class="Price"><i v-bind:class="decideClass()"></i> {{price}}</p>
        <p v-else-if="currency === 'EUR'" class="Price"><i v-bind:class="decideClass()"></i> {{price}}</p>
    `,
    created() {
    },
    props: ['price', 'currency'],
    methods: {
        decideClass() {
            if (this.currency === 'USD') {
                return 'currencySign fas fa-dollar-sign';
            } else if (this.currency === 'EUR') {
                return 'currencySign fas fa-euro-sign';
            }
        }
    },
};

const LoadMore = {
    data: function() {
        return {
            selected: false,
            pages: {},
        }
    },
    template: `<div class="LoadMoreWrapper">
               </div>`,
    methods: {
    }
};

export const EbayItems = {
    data: function() {
        return {
        }
    },
    template: `
            <div class="EbayItems" id="EbayItemsId">
                <div v-for="(item, index) in ebaySearchListing.items" :key="index" class="EbayItem SearchItem">
                    <div class="Row ImageWrapper">
                        <img class="Image" :src="item.image.url" />
                    </div>
                    
                    <div class="Row TitleWrapper">
                        <p>{{item.title.truncated}}</p>
                    </div>
                    
                    <div class="Row PriceWrapper">
                        <price 
                            v-bind:price="item.price.price" 
                            v-bind:currency="item.price.currency">
                        </price>
                    </div>
                    
                    <div class="Row QuickLookWrapper">
                        <button>Quick look<i class="fas fa-caret-right"></i></button>
                    </div>
                    
                    <div class="Row FullDetailsWrapper">
                        <button>Full details<i class="fas fa-caret-right"></i></button>
                    </div>
                    
                    <div class="Row MarketplaceWrapper">
                        <a :href="item.viewItemUrl" target="_blank">View on eBay</a>
                    </div>
                </div>
            </div>
            `,
    props: ['classList'],
    computed: {
        ebaySearchListing: function() {
            const ebaySearchListing = this.$store.state.ebaySearchListing;

            if (ebaySearchListing === null) {
                return [];
            }

            return ebaySearchListing;
        }
    },
    components: {
        'item': Item,
        'price': Price,
        'load-more': LoadMore,
    }
};