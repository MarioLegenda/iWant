import {Item} from "../../../Listing/components/Item";

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
                        <p class="Price">{{item.price.price}}</p>
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
        'load-more': LoadMore,
    }
};