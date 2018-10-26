import {SearchComponent} from "./SearchComponent/SearchComponent";
import {RepositoryFactory} from "../../services/repositoryFactory";
import {ListingComponent} from "./ListingComponent/ListingComponent";
import {Filters} from "./SearchComponent/Filters";

export const SearchPage = {
    data: function() {
        return {
            currentEbayGlobalId: null,
            ebayHttpInProgress: false,
            showMarketplaceChoices: false,
            foundEbayGlobalIds: [],
            marketplaceChoices: {
                ebay: false,
                etsy: false,
            },
        }
    },
    template: `<div id="search_page">
                    <input type="hidden" :input="searchInitialiseEvent" />
         
                    <filters>
                    </filters>
                    
                    <search-component
                        v-bind:external-search-term="searchTerm">
                    </search-component>
                    
                    <listing-component></listing-component>

               </div>`,
    computed: {
        searchTerm: function() {
            return this.$store.state.searchTerm;
        },

        searchInitialiseEvent: function() {
            const searchInitialiseEvent = this.$store.state.searchInitialiseEvent;

            if (searchInitialiseEvent.initialised) {
                this.onGetEbayItems(searchInitialiseEvent.model);
            }
        },
    },
    methods: {
        onGetEbayItems(model) {
            if (this.ebayHttpInProgress === false) {
                const searchRepo = RepositoryFactory.create('search');

                setTimeout(() => {
                    searchRepo.searchEbay(model, (response) => {
                        if (response.status >= 400 && response.status <= 499 ||
                            response.status >= 500 && response.status <= 599) {

                            return;
                        }

                        this.$store.commit('listingEvent', {
                            ebay: response.collection.views.globalIdView
                        });


                    }, (response) => {

                    });
                }, 500);

                this.ebayHttpInProgress = true;
            }
        },

        scrollIfNotScrolled() {
            const mm = document.getElementById('main_menu');
            const mh = document.getElementById('main_header');
            const as = document.getElementById("AdvancedSearchId");

            setTimeout(function() {
                window.scrollTo({
                    top: getElementGeometry(mm).height + getElementGeometry(mh).height + getElementGeometry(as).height,
                    behavior: 'smooth',
                });
            }, 1000);
        },
    },
    components: {
        'listing-component': ListingComponent,
        'search-component': SearchComponent,
        'filters': Filters,
    }
};