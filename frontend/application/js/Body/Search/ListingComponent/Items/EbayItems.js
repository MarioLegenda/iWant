import {Item} from "../../../Listing/components/Item";
import {SUPPORTED_SITES} from "../../../../supportedSites";
import urlifyFactory from 'urlify';
import {Price} from "../../../../services/util";
import { SyncLoader } from '@saeris/vue-spinners'
import { GridLoader } from '@saeris/vue-spinners'

const ImageItem = {
    template: `
               <div class="Row ImageWrapper">
                   <img class="Image" :src="determineImage()" />
               </div>`,
    mounted() {
        const img = this.$el.getElementsByTagName('img')[0];
        applyImageGeometry(this.url, function() {
            const geo = { width: this.naturalWidth, height: this.naturalHeight };

            if (geo.width > 240 || geo.height > 240) {
                img.style.width = `240px`;
                img.style.height = `240px`;

                return null;
            }

            img.style.width = `${geo.width}px`;
            img.style.height = `${geo.height}px`;
        });
    },
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
                        @click="loadMore">Load more
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
            return this.$store.getters.getPreparedSearchMetadata;
        }
    },
    methods: {
        loadMore: function() {
            let model = Object.assign({}, this.model);

            model.pagination.page = ++this.page;

            model.globalId = this.globalId;

            if (model.filters.doubleLocaleSearch) {
                const totalItems = this.getPreparedSearchMetadata.totalItems;
                const internalLimitIncrease = this.page * this.limit;

/*                if (internalLimitIncrease >= totalItems) {
                    this._increasePagination(model);

                    this.$emit('load-more', model);

                    return;
                }*/
            }

            const internalLimitIncrease = this.page * this.limit;

/*            if (internalLimitIncrease >= this.internalLimit) {
                this._increasePagination(model);

                this.$emit('load-more', model);

                return;
            }*/

            this.$emit('load-more', model);
        },

        _increasePagination(model) {
            model.internalPagination.page = ++this.internalPage;
            model.pagination.page = 1;
            this.page = 1;
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

export const SortModal = {
    data: function() {
        return {
            selected: 'bestMatch',
            bestMatch: {
                text: '',
                name: 'bestMatch',
            },
            newlyListed: {
                text: '',
                name: 'newlyListed',
            }
        }
    },

    created() {
        const sortingMethod = this.getFilters.sortingMethod;

        this.bestMatch.text = this.translationsMap.sorting.bestMatchTitle;
        this.newlyListed.text = this.translationsMap.sorting.newlyListedTitle;

        this.selected = this[sortingMethod].text;
    },
    template: `<div class="SortingWrapper">
                   <div class="SortingInfoWrapper" @click="showModal">
                       <h1>{{translationsMap.sorting.sortByTitle}}: {{selected}} <i class="fas fa-chevron-down"></i></h1>
                   </div>
                   
                   <modal name="sort-by-modal" :width="400" height="auto">
                       <div class="SortingComponent">
                       
                           <i @click="closeModal" class="CloseSortingModal fas fa-times"></i>
                           
                           <h1>{{translationsMap.sorting.sortByTitle}}: <i class="fas fa-sort-amount-up"></i></h1>  
                                                   
                           <div class="SortingChoiceWrapper">
                               <p @click="changeSortMethod('bestMatch')">{{translationsMap.sorting.bestMatchTitle}}</p>
                               <p @click="changeSortMethod('newlyListed')">{{translationsMap.sorting.newlyListedTitle}}</p>
                           </div>
                           
                       </div>
                   </modal>
               </div>`,

    watch: {
        getFilters: (prev, next) => {}
    },

    computed: {
        translationsMap: function() {
            return this.$store.state.translationsMap;
        },

        getFilters: function() {
            return this.$store.getters.getFilters;
        }
    },

    methods: {
        showModal() {
            this.$modal.show('sort-by-modal');
        },

        changeSortMethod(sortMethod) {
            this.selected = this[sortMethod].text;

            this.$emit('sorting-method-changed', sortMethod);

            this.$modal.hide('sort-by-modal');
        },

        closeModal() {
            this.$modal.hide('sort-by-modal');
        }
    }
};

const ListingAction = {
    template: `
            <div class="ListingActionWrapper">
               <div class="SiteName">
                   <img :src="decideImage()" />
                   <h1>{{decideTitle()}}</h1>
               </div>
               
               <sorting v-on:sorting-method-changed="sortingMethodChanged"></sorting>
            </div>`,
    props: ['siteInformation'],
    methods: {
        decideImage() {
            const globalId = this.siteInformation.global_id;

            return SUPPORTED_SITES.find(globalId).icon;
        },

        decideTitle() {
            return this.siteInformation.site_name;
        },

        sortingMethodChanged(sortingMethod) {
            this.$store.dispatch('changeSortingMethod', sortingMethod);
        },
    },
    components: {
        'sorting': SortModal,
    }
};

const LoadingText = {
    computed: {
        preparingProductsLoading: function() {
            return this.$store.state.preparingProductsLoading;
        },

        translatingProductsLoading: function() {
            return this.$store.state.translatingProductsLoading;
        },

        loadingText: function() {
            if (this.preparingProductsLoading) {
                return this.translationsMap.loading.itemsLoadingText;
            }

            if (this.translatingProductsLoading) {
                return this.translationsMap.loading.itemsTranslatingText;
            }
        },

        translationsMap: function() {
            return this.$store.state.translationsMap;
        },
    },
    template: `<p>{{loadingText}}</p>`
};

const NoItemsFound = {
    computed: {
        ebay404EmptyResult: function() {
            return this.$store.state.ebay404EmptyResult;
        },

        text: function() {
            if (this.ebay404EmptyResult) {
                return this.translationsMap.noItemsFound;
            }
        },

        translationsMap: function() {
            return this.$store.state.translationsMap;
        },
    },
    template: `<p>{{text}}</p>`
};

const BusinessEntity = {
    template: `
                <p class="BusinessEntityWrapper">
                    {{entityData.sellerUsername}}
                </p>`,
    props: ['entityData'],
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
                        filters: state.filtersEvent,
                    });

                    console.log(model);

                    model.pagination = { limit: 8, page: 1 };
                    model.internalPagination = { limit: 80, page: 1 };

                    this.$store.dispatch('totalListingUpdate', {
                        model: model,
                        searchRepo: this.$repository.SearchRepository
                    });

                    const timeout = (this.$isMobile) ? 500 : 0;

                    setTimeout(() => scrollToElement(document.getElementById('EbayResultsLoadingId'), 200), timeout);
                }
            }
        });
    },
    template: `
            <div class="EbayItemsWrapper">                        
                <div v-if="isListingInitialised" class="EbayItems" id="EbayItemsId">
                
                    <listing-action v-bind:site-information="getSiteInformation"></listing-action>
                    
                    <div v-for="(item, index) in getTotalListings" :key="index" class="EbayItem SearchItem">
                    
                        <div class="ItemLeftPanel">
                            <image-item :url="item.image.url"></image-item>
                        </div>
                       
                        <div class="ItemRightPanel">
                            <business-entity :entity-data="item.businessEntity"></business-entity>

                            <div class="Row TitleWrapper">
                                <h1>{{item.title.original}}</h1>
                            </div>
                        
                            <div class="Row CountryOfOrigin">
                                <h1>{{_determineCountry(item.country)}}</h1>
                            </div>
                    
                            <div class="Row PriceWrapper">
                                <price
                                    v-bind:price="item.price.price"
                                    v-bind:currency="item.price.currency">
                                </price>
                            </div>
                            
                            <div class="Row FullDetailsWrapper">
                                <a :href="_generateSingleItemLink(item)" class="FullDetailsButton" @click="goToSingleItem(item, $event)">{{getTranslationsMap.searchItem.fullDetailsTitle}}<i class="fas fa-info"></i></a>
                            </div>
                    
                            <div class="Row MarketplaceWrapper">
                                <a :href="item.viewItemUrl" target="_blank">{{getTranslationsMap.searchItem.viewOnEbay}}</a>
                            </div>
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
                    <div class="GridLoader">
                         <grid-loader :size="20" sizeUnit="px" color="#eb1d25"></grid-loader>
                    </div>
                    
                    <div class="TextLoader">
                        <loading-text></loading-text>
                    </div>
                </div>
                
                <div v-if="ebay404EmptyResult" class="NoItemsFound">
                    <div class="TextLoader">
                        <no-items-found></no-items-found>
                    </div>
                </div>
            </div>
            `,
    props: ['classList'],
    watch: {
        isListingInitialised: (prev, next) => {},

        getSiteInformation: (prev, next) => {},

        getTotalListings: (prev, next) => {},

        getModel: (prev, next) => {},

        getFilters: (prev, next) => {},
    },
    computed: {
        getEbaySearchListingLoading() {
            return this.$store.state.ebaySearchListingLoading;
        },

        ebay404EmptyResult: function() {
            return this.$store.state.ebay404EmptyResult;
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

            const searchRepo = this.$repository.SearchRepository;

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

        goToSingleItem(item, $event) {
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
                    name: urlify(item.title.original),
                }
            });

            $event.preventDefault();

            return false;
        },

        _generateSingleItemLink: function(item) {
            const urlify = urlifyFactory.create({
                addEToUmlauts: true,
                szToSs: true,
                spaces: "-",
                nonPrintable: "-",
                trim: true
            });

            return `/${this.$localeInfo.locale}/item/${urlify(item.title.original)}/${item.itemId}`;
        },

        _determineCountry(country) {
            if (!country.isAvailable) {
                return 'Country not specified';
            }

            let resolvedCountry = country.name;
            if (country.name === 'United Kingdom of Great Britain and Northern Ireland') {
                resolvedCountry = 'United Kingdom';
            } else if (country.name === 'United States of America') {
                resolvedCountry = 'USA';
            }

            return `${this.getTranslationsMap.searchItem.fromTitle} ${resolvedCountry}`;
        },
    },
    components: {
        'item': Item,
        'price': Price,
        'load-more': LoadMore,
        'listing-action': ListingAction,
        'image-item': ImageItem,
        'grid-loader': GridLoader,
        'sort-modal': SortModal,
        'loading-text': LoadingText,
        'business-entity': BusinessEntity,
        'no-items-found': NoItemsFound,
    }
};