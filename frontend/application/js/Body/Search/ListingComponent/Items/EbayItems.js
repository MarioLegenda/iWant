import {Item} from "../../../Listing/components/Item";
import {SUPPORTED_SITES} from "../../../../global";
import {RepositoryFactory} from "../../../../services/repositoryFactory";

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
    props: ['pagination', 'currentlyLoading'],
    template: `<div class="LoadMoreWrapper">
                   <p 
                        class="LoadMoreButton"
                        @click="loadMore">Load more <i v-if="!currentlyLoading" class="fas fa-chevron-down"></i><i v-if="currentlyLoading" class="CurrentlyLoading fas fa-circle-notch fa-spin"></i>
                   </p>
               </div>`,
    methods: {
        loadMore: function() {
            this.$emit('load-more', Object.assign({}, this.pagination, {
                page: ++this.pagination.page
            }));
        }
    }
};

export const EbayItems = {
    data: function() {
        return {
            currentlyLoading: false,
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
                
                <load-more 
                    @load-more="onLoadMore"
                    :pagination="ebaySearchListing.pagination"
                    :currently-loading="currentlyLoading">
                </load-more>
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
        },
        filtersEvent: function() {
            const filtersEvent = this.$store.state.filtersEvent;

            if (filtersEvent === null) {
                return null;
            }

            return this.$store.state.filtersEvent;
        },
    },
    methods: {
        onLoadMore: function(pagination) {
            this.currentlyLoading = true;

            const searchRepo = RepositoryFactory.create('search');
            const uniqueName = this.ebaySearchListing.preparedData.uniqueName;

            searchRepo.getPreparedEbaySearch({
                uniqueName: uniqueName,
                globalId: this.ebaySearchListing.preparedData.globalId,
                locale: this.$localeInfo.locale,
                lowestPrice: this.filtersEvent.lowestPrice,
                pagination: pagination
            }, (r) => {
                const fetchedItems = r.collection.data;
                let existingData = this.ebaySearchListing.items;

                fetchedItems.map((i) => existingData.push(i));

                this.$store.commit('ebaySearchListing', {
                    items: existingData,
                    pagination: r.collection.pagination,
                    preparedData: this.ebaySearchListing.preparedData,
                });

                this.currentlyLoading = false;
            });
        },
    },
    components: {
        'item': Item,
        'price': Price,
        'load-more': LoadMore,
        'site-name': SiteName,
        'image-item': ImageItem,
    }
};