import {Item} from "../../../Listing/components/Item";
import {SUPPORTED_SITES} from "../../../../supportedSites";
import {RepositoryFactory} from "../../../../services/repositoryFactory";
import urlifyFactory from 'urlify';
import {Price} from "../../../../services/util";
import { SyncLoader } from '@saeris/vue-spinners'
import { GridLoader } from '@saeris/vue-spinners'


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
                        v-if="!currentlyLoading"
                        class="LoadMoreButton"
                        @click="loadMore">Load more</i>
                   </p>
                   
                   <sync-loader v-if="currentlyLoading" class="CurrentlyLoading" color="#f44d00" :size="10" siteUnit="px"></sync-loader>
               </div>`,
    watch: {
        globalId: function(newVal, oldVal) {
            this.resetPagination();

            return newVal;
        },
        getFilters: (prev, next) => {},
        getPreparedSearchMetadata(prev, next) {}
    },
    computed: {
        getFilters: function() {
            this.resetPagination();
        },
        getPreparedSearchMetadata() {

        }
    },
    methods: {
        loadMore: function() {
            let model = Object.assign({}, this.model);

            model.pagination.page = ++this.page;

            model.globalId = this.globalId;

            const internalLimitIncrease = this.page * this.limit;

            if (internalLimitIncrease >= this.internalLimit) {
                model.internalPagination.page = ++this.internalPage;
                model.pagination.page = 1;
                this.page = 1;

                this.$emit('load-more', model);

                return;
            }

            this.$emit('load-more', model);
        },

        resetPagination() {
            this.limit = 8;
            this.page = 1;
            this.internalLimit = 80;
            this.internalPage = 1;
        }
    },
    components: {
        'sync-loader': SyncLoader,
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
                                        <button class="PopoverButton" @click="goToSingleItem(item)">{{translationsMap.searchItem.fullDetailsTitle}}</button>
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

export const EbayItems = {
    data: function() {
        return {
            currentlyLoading: false,
        }
    },
    created() {
        this.$store.subscribe((mutation, state) => {
            if (mutation.type === 'filtersEvent') {
                if (this.isListingInitialised) {
                    let model = Object.assign({}, this.getModel, {
                        filters: this.getFilters,
                    });

                    model.pagination = { limit: 8, page: 1 };
                    model.internalPagination = { limit: 80, page: 1 };

                    this.$store.dispatch('totalListingUpdate', model);

                    const timeout = (this.$isMobile) ? 500 : 0;

                    setTimeout(() => scrollToElement(document.getElementById('EbayResultsLoadingId'), 200), timeout);
                }
            }
        });
    },
    template: `
            <div class="EbayItemsWrapper">                                
                <div v-if="isListingInitialised" class="EbayItems" id="EbayItemsId">
                    <site-name v-bind:site-information="getSiteInformation"></site-name>
                    <div v-for="(item, index) in getTotalListings" :key="index" class="EbayItem SearchItem">
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
                        :model="getModel"
                        :global-id="getSiteInformation.global_id">
                    </load-more>
                </div>
                
                <div v-if="getEbaySearchListingLoading" id="EbayResultsLoadingId" class="EbayResultsLoading">
                    <grid-loader :size="20" sizeUnit="px" color="#f44d00"></grid-loader>
                </div>
            </div>
            `,
    props: ['classList'],
    watch: {
        isListingInitialised: (prev, next) => {
        },

        getSiteInformation: (prev, next) => {
        },

        getTotalListings: (prev, next) => {
        },

        getModel: (prev, next) => {
        },

        getFilters: (prev, next) => {
        }
    },
    computed: {
        getEbaySearchListingLoading() {
            return this.$store.state.ebaySearchListingLoading;
        },

        getTotalListings: function() {
            return this.$store.getters.getTotalListings;
        },

        isListingInitialised() {
            return this.$store.getters.isListingInitialised;
        },

        getSearchListing: function() {
            return this.$store.getters.getSearchListing;
        },

        getSiteInformation: function() {
            return this.$store.getters.getSiteInformation;
        },

        getModel: function() {
            return this.$store.getters.getModel;
        },

        getFilters: function() {
            return this.$store.getters.getFilters;
        },

        getTranslationsMap: function() {
            return this.$store.state.translationsMap;
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
                                this.$store.commit('totalListing', r.collection.data.items);
                                this.currentlyLoading = false;
                            });
                        });
                        break;
                    case 'GET':
                        searchRepo.getProducts(model, (r) => {
                            this.$store.commit('totalListing', r.collection.data.items);
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
        'grid-loader': GridLoader,
    }
};