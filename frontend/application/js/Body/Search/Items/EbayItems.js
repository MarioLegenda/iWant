import {Item} from "../../Listing/components/Item";
import {RepositoryFactory} from "../../../services/repositoryFactory";

const LoadMore = {
    data: function() {
        return {
            selected: false,
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

            const searchRepo = RepositoryFactory.create('search');
            const model = this.createModel();

            searchRepo.searchEbay(model, (response) => {
                this.$store.commit('ebaySearchListing', {
                    listing: response.collection.views.globalIdsView,
                    pagination: response.collection.pagination,
                    model: model,
                });
            });

            this.$emit('on-load-items', []);
        },
        createModel() {
            this.requestModel.filters.globalIds.push(this.globalId);
            this.requestModel.pagination.page += this.requestModel.pagination.page;

            return this.requestModel;
        }
    }
};

export const EbayItems = {
    data: function() {
        return {
            loadMoreData: [],
        }
    },
    template: `
            <div v-if="ebaySearchListingLoaded()" class="EbayItems SearchItems" id="EbayItemsId">
                <div v-for="(item, index) in ebaySearchListing.listing" :key="index" class="GlobalIdContainer">
                    <h1 class="SearchItems_GlobalIdIdentifier">{{item.globalIdInformation.site_name}}</h1>
                    
                    <item
                        v-for="(item, index) in item.items" 
                        :key="index"
                        v-bind:item="item"
                        v-bind:classList="classList"
                        v-bind:show="{taxonomyTitle: false, marketplaceLogo: false}">
                    </item>
                    
                    <load-more
                        v-bind:global-id="item.globalIdInformation.global_id"
                        v-bind:request-model="ebaySearchListing.model"
                        v-on:on-load-items="onLoadItems">
                    </load-more>
                </div>
            </div>
            `,
    methods: {
        onLoadItems(items) {

        },
        ebaySearchListingLoaded() {
            return typeof this.ebaySearchListing !== 'undefined';
        }
    },
    props: ['classList'],
    computed: {
        ebaySearchListing: function() {
            return this.$store.state.ebaySearchListing;
        }
    },
    components: {
        'item': Item,
        'load-more': LoadMore,
    }
};