import {Item} from "../../../Listing/components/Item";
import {SUPPORTED_SITES} from "../../../../supportedSites";
import {RepositoryFactory} from "../../../../services/repositoryFactory";
import urlifyFactory from 'urlify';
import {Price} from "../../../../services/util";
import {mapGetters} from 'vuex'

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
        this.resetPagination();
    },
    props: ['currentlyLoading', 'model', 'globalId'],
    template: `<div class="LoadMoreWrapper">
                   <p
                        class="LoadMoreButton"
                        @click="loadMore">Load more <i v-if="!currentlyLoading" class="fas fa-chevron-down"></i><i v-if="currentlyLoading" class="CurrentlyLoading fas fa-circle-notch fa-spin"></i>
                   </p>
               </div>`,
    watch: {
        globalId: function(newVal, oldVal) {
            this.resetPagination();

            return newVal;
        }
    },
    methods: {
        loadMore: function() {
            this.model.pagination.page = ++this.page;

            const internalLimitIncrease = this.page * this.limit;

            if (internalLimitIncrease >= this.internalLimit) {
                this.model.internalPagination.page = ++this.internalPage;
                this.model.pagination.page = 1;
                this.page = 1;

                this.$emit('load-more', this.model);

                return;
            }

            this.$emit('load-more', this.model);
        },

        resetPagination() {
            this.limit = 8;
            this.page = 1;
            this.internalLimit = 80;
            this.internalPage = 1;
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
    props: ['itemId', 'popoverButtonOptions'],
    template: `
                   <div class="QuickLookWrapper">
                       <v-popover :open="showPopover" offset="16">
                           <button :class="popoverButtonOptions.className" @click="loadItem">{{popoverButtonOptions.title}}<i v-if="popoverButtonOptions.includeIcon" class="fas fa-caret-right"></i></button>

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

const Title = {
    template: `
        <div class="Row TitleWrapper">
            <h1>{{titleText}}</h1>
        </div>
    `,
    props: ['titleText']
};

export const EbayItems = {
    data: function() {
        return {
            currentlyLoading: false,
            model: null
        }
    },
    template: `
            <div class="EbayItemsWrapper">                
                <div v-if="getSearchListing.items !== null && getSearchListing.siteInformation !== null" class="EbayItems" id="EbayItemsId">
                    <site-name v-bind:site-information="getSearchListing.siteInformation"></site-name>
                    <div v-for="(item, index) in getSearchListing.items" :key="index" class="EbayItem SearchItem">
                        <image-item :url="item.image.url"></image-item>
                    
                        <div class="Row TitleWrapper">
                            <h1>{{chooseTitle(item.title)}}</h1>
                        </div>
                    
                        <div class="Row PriceWrapper">
                            <price
                                v-bind:price="item.price.price"
                                v-bind:currency="item.price.currency">
                            </price>
                        </div>
                    
                        <quick-look
                            :item-id="item.itemId"
                            :popover-button-options="{className: 'tooltip-target b3 PopoverButton', title: getTranslationsMap.searchItem.quickLookTitle, includeIcon: true}">
                        </quick-look>
                    
                        <div class="Row FullDetailsWrapper">
                            <button class="FullDetailsButton" @click="goToSingleItem(item)">{{getTranslationsMap.searchItem.fullDetailsTitle}}<i class="fas fa-caret-right"></i></button>
                        </div>
                    
                        <div class="Row MarketplaceWrapper">
                            <a :href="item.viewItemUrl" target="_blank">{{getTranslationsMap.searchItem.viewOnEbay}}</a>
                        </div>
                    </div>
                
                    <load-more
                        @load-more="onLoadMore"
                        :currently-loading="currentlyLoading"
                        :model="model"
                        :global-id="getSearchListing.siteInformation.global_id">
                    </load-more>
                </div>
                
                <div v-if="getEbaySearchListingLoading" class="EbayResultsLoading">
                    {{getTranslationsMap.loadingSearchResults}}
                </div>
            </div>
            `,
    props: ['classList'],
    watch: {
        getSearchInitialiseEvent: (prev, next) => {
        },

        getSearchListing: (prev, next) => {
        },

        getRangeEvent: (prev, next) => {
        },

        getMoreLoadedSearchListings: (prev, next) => {
        },
    },
    computed: {
        getEbaySearchListingLoading() {
            return this.$store.state.ebaySearchListingLoading;
        },

        getSearchInitialiseEvent() {
            const searchInitialiseEvent = this.$store.state.searchInitialiseEvent;

            if (searchInitialiseEvent === null) {
                return null;
            }

            this.model = searchInitialiseEvent.model;

            return searchInitialiseEvent;
        },

        getRangeEvent: function() {
            const rangeEvent = this.$store.state.rangeEvent;

            if (rangeEvent === null) {
                return null;
            }

            if (this.$store.state.searchInitialiseEvent === null) {
                return null;
            }

            if (this.$store.state.searchInitialiseEvent.initialised === false) {
                return null;
            }

            if (rangeEvent.lowestPrice === true) {
                let model = Object.assign({}, this.model);

                model.filters.lowestPrice = true;

                if (this.$store.state.ebaySearchListing.items === null) {
                    return null;
                }

                model.filters.lowestPrice = true;

                model.filters.lowestPrice = true;
                model.range = {
                    from: 1,
                    to: this.getSearchListing.items.length,
                };

                this.$store.commit('ebaySearchListingLoading', true);

                const searchRepo = RepositoryFactory.create('search');

                searchRepo.getProductsByRange(model, (r) => {
                    this.$store.commit('ebaySearchListingLoading', false);
                    this.$store.commit('ebaySearchListing', r.collection.data);
                });
            } else if (rangeEvent.lowestPrice === false) {
            }

            return rangeEvent;
        },

        getSearchListing: function() {
            console.log('getSearchListing called');
            const ebaySearchListing = this.$store.getters.getSearchListing;

            if (ebaySearchListing.siteInformation === null) {
                return ebaySearchListing;
            }

            return ebaySearchListing;
        },

        getTranslationsMap: function() {
            return this.$store.state.translationsMap;
        },

        getMoreLoadedSearchListings: function() {
            console.log('load more event');
            const loadMoreSearchListing = this.$store.state.loadMoreSearchListing;

            if (loadMoreSearchListing.siteInformation === null) {
                return loadMoreSearchListing;
            }

            const concatItems = (items) => {
                for (const item of items) {
                    this.getSearchListing.items.push(item);
                }
            };

            concatItems(loadMoreSearchListing.items);

            return loadMoreSearchListing;
        },
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
                                this.$store.commit('loadMoreSearchListing', r.collection.data);
                                this.currentlyLoading = false;
                            });
                        });
                        break;
                    case 'GET':
                        searchRepo.getProducts(model, (r) => {
                            this.$store.commit('loadMoreSearchListing', r.collection.data);
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
        },

        chooseTitle(title) {
            if (this.$viewportDimensions.width < 480) {
                return title.original;
            }

            return title.truncated;
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