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
        }
    },
    template: `
            <div class="EbayItemsWrapper">
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
                        :pagination="ebaySearchListing.pagination"
                        :currently-loading="currentlyLoading">
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
        translationsMap: function() {
            return this.$store.state.translationsMap;
        }
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