import {Item} from "../../Listing/components/Item";
import {RepositoryFactory} from "../../../services/repositoryFactory";

const LoadMore = {
    data: function() {
        return {
            selected: false,
            pages: {},
        }
    },
    template: `<div class="LoadMoreWrapper">
                   <p
                        @click="loadMore"
                        class="LoadMore">
                        <span v-if="selected === false">Load more</span>
                        <i v-if="selected === true" class="fas fa-circle-notch fa-spin"></i>
                   </p>
               </div>`,
    props: ['requestModel', 'globalId'],
    methods: {
        loadMore() {
            this.selected = !this.selected;
            this.updatePages();

            const searchRepo = RepositoryFactory.create('search');
            const model = this.createModel();

            searchRepo.searchEbay(model, (response) => {
                this.$store.commit('ebaySearchListing', {
                    listing: response.collection.views.globalIdView,
                    model: model,
                });

                this.selected = false;
            });
        },
        createModel() {
            this.requestModel.filters.globalIds.push(this.globalId);
            this.requestModel.pagination = {
                limit: 4,
                page: this.pages[this.globalId],
            };

            return this.requestModel;
        },
        updatePages() {
            if (!this.pages.hasOwnProperty(this.globalId)) {
                this.pages[this.globalId] = 2;
            } else {
                this.pages[this.globalId] += 1;
            }
        }
    }
};

export const EbayItems = {
    data: function() {
        return {
            loadMoreData: [],
            items: {},
            globalIdInfo: {},
        }
    },
    created() {
        RepositoryFactory.create('app').asyncGetEbayGlobalIdsInformation((data) => {
            const globalIdInfo = data.collection.data;

            this.globalIdInfo = globalIdInfo;

            for (let key in globalIdInfo) {
                if (globalIdInfo.hasOwnProperty(key)) {
                    const info = globalIdInfo[key];

                    this.items[info.global_id] = [];
                    this.$set(this.items, info.global_id, []);
                }
            }
        });
    },
    template: `
            <div v-if="ebaySearchListing" class="EbayItems SearchItems" id="EbayItemsId">
                <input type="hidden" :value="ebaySearchListing" />
                <div v-if="products.length > 0" v-for="(products, globalId, index) in items" :key="index" class="GlobalIdContainer">
                    <h1 class="SearchItems_GlobalIdIdentifier">{{findGlobalIdInfo(globalId).site_name}}</h1>
                    
                    <item
                        v-for="(item, index) in products" 
                        :key="index"
                        v-bind:item="item"
                        v-bind:classList="classList"
                        v-bind:show="{taxonomyTitle: false, marketplaceLogo: false}">
                    </item>
                    
                    <load-more
                        v-bind:global-id="findGlobalIdInfo(globalId).global_id"
                        v-bind:request-model="ebaySearchListing.model">
                    </load-more>
                </div>
            </div>
            `,
    methods: {
        findGlobalIdInfo(globalId) {
            const info = this.globalIdInfo[globalId.toLowerCase()];

            return info;
        }
    },
    props: ['classList'],
    computed: {
        ebaySearchListing: function() {
            const listing = this.$store.state.ebaySearchListing.listing;

            if (listing.length === 0) {
                for (let key in this.globalIdInfo) {
                    if (this.globalIdInfo.hasOwnProperty(key)) {
                        const info = this.globalIdInfo[key];

                        this.items[info.global_id] = [];
                        this.$set(this.items, info.global_id, []);
                    }
                }
            }

            let globalIds = [];
            for (const globalId in listing) {
                if (this.items.hasOwnProperty(globalId)) {
                    this.items[globalId] = this.items[globalId].concat(listing[globalId]);
                    this.$set(this.items, globalId, this.items[globalId]);

                    globalIds.push(this.globalIdInfo[globalId.toLowerCase()]);
                }
            }

            this.$emit('on-global-ids-computed', globalIds);

            return this.$store.state.ebaySearchListing;
        }
    },
    components: {
        'item': Item,
        'load-more': LoadMore,
    }
};