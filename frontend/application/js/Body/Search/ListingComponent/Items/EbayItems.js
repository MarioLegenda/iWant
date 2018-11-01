import {Item} from "../../../Listing/components/Item";
import {SUPPORTED_SITES} from "../../../../global";

export const Price = {
    template: `
        <p v-if="currency === 'USD'" class="Price"><i v-bind:class="decideClass()"></i> {{price}}</p>
        <p v-else-if="currency === 'EUR'" class="Price"><i v-bind:class="decideClass()"></i> {{price}}</p>
        <p v-else-if="currency === 'GBP'" class="Price"><i v-bind:class="decideClass()"></i> {{price}}</p>
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
            } else if (this.currency === 'GBP') {
                return 'currencySign fas fa-pound-sign';
            }
        }
    },
};

const SiteName = {
    template: `<div class="SiteName">
                   <img :src="decideImage()" />
                   <h1>{{decideTitle()}}</h1>
               </div>`,
    props: ['globalIdInformation'],
    methods: {
        decideImage() {
            const globalId = this.globalIdInformation.global_id;

            return SUPPORTED_SITES.find(globalId).icon;
        },
        decideTitle() {
            return this.globalIdInformation.site_name;
        }
    }
};

const ImageItem = {
    template: `
               <div class="Row ImageWrapper">
                   <img class="Image" :src="determineImage()" />
               </div>`,
    props: ['url'],
    methods: {
        determineImage() {
            if (this.url === 'NaN') {
                return '/images/no-image.png';
            }

            return this.url;
        }
    }
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
            <div v-if="ebaySearchListing !== null" class="EbayItems" id="EbayItemsId">
                <site-name v-bind:global-id-information="ebaySearchListing.preparedData.globalIdInformation"></site-name>
                <div v-for="(item, index) in ebaySearchListing.items" :key="index" class="EbayItem SearchItem">
                    <image-item :url="item.image.url"></image-item>
                    
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
                return null;
            }

            return ebaySearchListing;
        }
    },
    components: {
        'item': Item,
        'price': Price,
        'load-more': LoadMore,
        'site-name': SiteName,
        'image-item': ImageItem,
    }
};