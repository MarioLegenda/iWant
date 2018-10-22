import {Item} from "../../Listing/components/Item";
import {RepositoryFactory} from "../../../services/repositoryFactory";

const LoadMore = {
    data: function() {
        return {
            selected: false,
            page: 1,
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
    props: ['requestModel'],
    methods: {
        loadMore() {
            this.selected = !this.selected;
            this.updatePages();

            const searchRepo = RepositoryFactory.create('search');
            const model = this.createModel();

            searchRepo.searchEtsy(model, (response) => {
                this.$store.commit('etsySearchListing', {
                    listing: response.collection.data,
                    pagination: response.collection.pagination,
                    model: model,
                });

                this.selected = false;
            });
        },
        createModel() {
            this.requestModel.pagination = {
                limit: 4,
                page: this.page,
            };

            return this.requestModel;
        },
        updatePages() {
            ++this.page;
        }
    }
};

export const EtsyItems = {
    data: function() {
        return {
            loadMoreData: [],
            items: []
        }
    },
    template: `
            <div v-if="etsySearchListing.listing.length > 0" class="EtsyItems SearchItems" id="EtsyItemsId">
                <input type="hidden" :value="etsySearchListing" />
                <div class="GlobalIdContainer">
                    <h1 class="SearchItems_GlobalIdIdentifier"></h1>
                    
                    <item
                        v-for="(item, index) in items"
                        :key="index"
                        v-bind:item="item"
                        v-bind:classList="classList"
                        v-bind:show="{taxonomyTitle: false, marketplaceLogo: false}">
                    </item>
                    
                    <load-more
                        v-bind:request-model="etsySearchListing.model">
                    </load-more>
                </div>
            </div>
            `,
    props: ['classList'],
    computed: {
        etsySearchListing: function() {
            const etsySearchListing = this.$store.state.etsySearchListing;

            this.items = this.items.concat(etsySearchListing.listing);

            return this.$store.state.etsySearchListing;
        }
    },
    components: {
        'item': Item,
        'load-more': LoadMore,
    }
};