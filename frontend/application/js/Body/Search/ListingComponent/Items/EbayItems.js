import {Item} from "../../../Listing/components/Item";
import {SUPPORTED_SITES} from "../../../../supportedSites";
import {RepositoryFactory} from "../../../../services/repositoryFactory";
import urlifyFactory from 'urlify';
import {Price} from "../../../../services/util";

const SiteName = {
    template: `<div class="SiteName">
                   <img :src="decideImage()" />
                   <h1>{{decideTitle()}}</h1>
               </div>`,
    props: ['siteInformation'],
    methods: {
        decideImage() {
            const globalId = this.siteInformation.global_id;

            return SUPPORTED_SITES.find(globalId).icon;
        },
        decideTitle() {
            return this.siteInformation.site_name;
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
            limit: 8,
            page: 1,
            internalLimit: 80,
            internalPage: 1,
        }
    },
    created() {
        this.limit = this.model.pagination.limit;
        this.page = this.model.pagination.page;
        this.internalLimit = this.model.internalPagination.limit;
        this.internalPage = this.model.internalPagination.page;
    },
    props: ['currentlyLoading', 'model'],
    template: `<div class="LoadMoreWrapper">
                   <p
                        class="LoadMoreButton"
                        @click="loadMore">Load more <i v-if="!currentlyLoading" class="fas fa-chevron-down"></i><i v-if="currentlyLoading" class="CurrentlyLoading fas fa-circle-notch fa-spin"></i>
                   </p>
               </div>`,
    methods: {
        loadMore: function() {
            this.model.pagination.page = ++this.page;

            const internalLimitIncrease = this.page * this.limit;

            if (internalLimitIncrease >= this.internalLimit) {
                console.log('internal limit increase');
                this.model.internalPagination.page = ++this.internalPage;
                this.model.pagination.page = 1;
                this.page = 1;

                this.$emit('load-more', this.model);

                return;
            }

            this.$emit('load-more', this.model);
        }
    }
};

const QuickLook = {
    data: function() {
        return {
            item: null,
            showPopover: false
        }
    },
    template: `
                   <div class="QuickLookWrapper">
                       <v-popover :open="showPopover" offset="16">
                           <button class="tooltip-target b3" @click="loadItem">{{translationsMap.searchItem.quickLookTitle}}<i class="fas fa-caret-right"></i></button>

                           <template slot="popover">
                               <div v-close-popover class="Close">
                                   <i class="fas fa-times"></i>
                               </div>
                               
                               <div v-if="item === null" class="QuickLookLoader">
                                    <i class="fas fa-circle-notch fa-spin"></i>
                               </div>
                               
                               <div v-if="item !== null" class="QuickLookWindow">
                                   
                                   <div class="Row TitleWrapper">
                                       <h1>{{item.title}}</h1>
                                   </div>
                                   
                                   <div class="Row">
                                       <div>
                                           <span class="desc-left">{{translationsMap.quickLook.requiresImmediatePayment}}</span>
                                           <span class="desc-right">{{(item.autoPay === true) ? translationsMap.yes : translationsMap.no}}</span>
                                       </div>
                                   </div>
                                   
                                   <div class="Row">
                                       <div>
                                           <span class="desc-left">{{translationsMap.quickLook.endingOn}}</span>
                                           <span class="desc-right">{{ item.endTime | userFriendlyDate }}</span>
                                       </div>
                                   </div>
                                   
                                   <div class="Row">
                                       <div>
                                           <span class="desc-left">{{translationsMap.quickLook.seller}}</span>
                                           <span class="desc-right">{{item.seller.userId}}</span>
                                       </div>
                                   </div>
                                   
                                   <div class="Row">
                                       <div>
                                           <span class="desc-left">{{translationsMap.quickLook.quantity}}</span>
                                           <span class="desc-right">{{item.quantity}}</span>
                                       </div>
                                   </div>
                                   
                                   <div class="Independent">
                                        <button @click="goToSingleItem(item)">{{translationsMap.searchItem.fullDetailsTitle}}</button>
                                   </div>
                               </div>
                           </template>
                       </v-popover>
                   </div>
               `,
    computed: {
        translationsMap: function() {
            return this.$store.state.translationsMap;
        }
    },
    props: ['itemId'],
    methods: {
        loadItem: function() {
            this.showPopover = !this.showPopover;

            if (this.showPopover === false) {
                return;
            }

            if (this.item === null) {
                const singleItemRepo = RepositoryFactory.create('single-item');

                singleItemRepo.checkSingleItem({
                    itemId: this.itemId,
                    locale: this.$localeInfo.locale,
                }, (r) => {
                    const options = r.resource.data;

                    if (options.method === 'PUT') {
                        singleItemRepo.putSingleItem({
                            itemId: this.itemId,
                            route: options.route,
                            locale: this.$localeInfo.locale
                        }, (r) => this.item = r.resource.data.singleItem)
                    } else if (options.method === 'GET') {
                        singleItemRepo.getQuickLookSingleItem({
                            route: options.route
                        }, (r) => this.item = r.resource.data.singleItem);
                    }
                });
            }
        },

        goToSingleItem(item) {
            const urlify = urlifyFactory.create({
                addEToUmlauts: true,
                szToSs: true,
                spaces: "-",
                nonPrintable: "-",
                trim: true
            });

            this.$router.push({
                name: 'SingleItem',
                params: {
                    locale: this.$localeInfo.locale,
                    itemId: item.itemId,
                    name: (urlify(item.title)),
                }
            });
        },
    }
};

export const EbayItems = {
    data: function() {
        return {
            currentlyLoading: false,
            items: null,
            siteInformation: null,
            model: null
        }
    },
    template: `
            <div class="EbayItemsWrapper">
                <input type="hidden" :value="ebaySearchListing">
                <input type="hidden" :value="searchInitialiseEvent">
                
                <div v-if="items !== null" class="EbayItems" id="EbayItemsId">
                    <site-name v-bind:site-information="siteInformation"></site-name>
                    <div v-for="(item, index) in items" :key="index" class="EbayItem SearchItem">
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
                    
                        <quick-look :item-id="item.itemId"></quick-look>
                    
                        <div class="Row FullDetailsWrapper">
                            <button @click="goToSingleItem(item)">{{translationsMap.searchItem.fullDetailsTitle}}<i class="fas fa-caret-right"></i></button>
                        </div>
                    
                        <div class="Row MarketplaceWrapper">
                            <a :href="item.viewItemUrl" target="_blank">{{translationsMap.searchItem.viewOnEbay}}</a>
                        </div>
                    </div>
                
                    <load-more
                        @load-more="onLoadMore"
                        :currently-loading="currentlyLoading"
                        :model="model">
                    </load-more>
                </div>
                
                <div v-if="ebaySearchListingLoading" class="EbayResultsLoading">
                    {{translationsMap.loadingSearchResults}}
                </div>
            </div>
            `,
    props: ['classList'],
    computed: {
        ebaySearchListingLoading() {
            return this.$store.state.ebaySearchListingLoading;
        },

        searchInitialiseEvent() {
            const searchInitialiseEvent = this.$store.state.searchInitialiseEvent;

            this.model = searchInitialiseEvent.model;

            return searchInitialiseEvent;
        },

        ebaySearchListing: function() {
            const ebaySearchListing = this.$store.state.ebaySearchListing;

            if (ebaySearchListing === null) {
                this.items = null;
                this.siteInformation = null;

                return null;
            }

            const concatItems = (items) => {
                for (const item of items) {
                    if (!Array.isArray(this.items)) {
                        this.items = [];
                    }

                    this.items.push(item);
                }
            };

            this.siteInformation  = ebaySearchListing.siteInformation;

            setTimeout(() => {
                concatItems(ebaySearchListing.items);
            }, 200);

            return ebaySearchListing;
        },

        filtersEvent: function() {
            const filtersEvent = this.$store.state.filtersEvent;

            if (filtersEvent === null) {
                return null;
            }

            return this.$store.state.filtersEvent;
        },

        translationsMap: function() {
            return this.$store.state.translationsMap;
        }
    },
    methods: {
        onLoadMore: function(model) {
            this.currentlyLoading = true;

            const searchRepo = RepositoryFactory.create('search');

            searchRepo.optionsForProductListing(model, (r) => {
                const data = r.resource.data;

                switch (data.method) {
                    case 'POST':
                        searchRepo.postPrepareSearchProducts(JSON.stringify({
                            searchData: model,
                        })).then(() => {
                            searchRepo.getProducts(model).then((r) => {
                                this.$store.commit('ebaySearchListing', r.collection.data);
                                this.currentlyLoading = false;
                            });
                        });
                        break;
                    case 'GET':
                        searchRepo.getProducts(model, (r) => {
                            this.$store.commit('ebaySearchListing', r.collection.data);
                            this.currentlyLoading = false;
                        });
                        break;
                    default:
                        throw new Error(`Invalid option for search listing given. Method can only be POST or GET, ${data.method} given`)
                }
            });
        },

        goToSingleItem(item) {
            const urlify = urlifyFactory.create({
                addEToUmlauts: true,
                szToSs: true,
                spaces: "-",
                nonPrintable: "-",
                trim: true
            });

            this.$router.push({
                name: 'SingleItem',
                params: {
                    locale: this.$localeInfo.locale,
                    itemId: item.itemId,
                    name: (urlify(item.title.original)),
                }
            });
        }
    },
    components: {
        'item': Item,
        'price': Price,
        'load-more': LoadMore,
        'site-name': SiteName,
        'image-item': ImageItem,
        'quick-look': QuickLook,
    }
};