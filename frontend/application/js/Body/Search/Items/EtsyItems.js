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
                    listing: response.collection.data,
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

export const EtsyItems = {
    data: function() {
        return {
            loadMoreData: [],
        }
    },
    template: `
            <div v-if="etsySearchListing.length > 0" class="EtsyItems SearchItems" id="EtsyItemsId">
                <input type="hidden" :value="etsySearchListing" />
                <div class="GlobalIdContainer">
                    <h1 class="SearchItems_GlobalIdIdentifier">Etsy</h1>
                    
                    <item
                        v-for="(item, index) in etsySearchListing"
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
            return this.$store.state.etsySearchListing.listing;
        }
    },
    components: {
        'item': Item,
        'load-more': LoadMore,
    }
};