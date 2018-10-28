import {SearchComponent} from "./SearchComponent/SearchComponent";
import {RepositoryFactory} from "../../services/repositoryFactory";
import {ListingComponent} from "./ListingComponent/ListingComponent";
import {Filters} from "./SearchComponent/Filters";
import {ListingChoiceComponent} from "./ListingChoiceComponent/ListingChoiceComponent";

export const SearchPage = {
    data: function() {
        return {
            currentEbayGlobalId: null,
            ebayHttpInProgress: false,
        }
    },
    template: `<div id="search_page">
                    <input type="hidden" :input="searchInitialiseEvent" />
         
                    <filters></filters>
                    
                    <div class="LeftPanel">
                        <search-component
                            v-bind:external-search-term="searchTerm">
                        </search-component>
                        
                        <listing-choice-component></listing-choice-component>
                    
                        <listing-component></listing-component>
                    </div>

               </div>`,
    computed: {
        searchTerm: function() {
            return this.$store.state.searchTerm;
        },

        searchInitialiseEvent: function() {
            return this.$store.state.searchInitialiseEvent;
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

                        const view = response.collection.views.globalIdView;

                        this.$store.commit('listingEvent', {
                            ebay: {
                                listing: response.collection.views.globalIdView,
                                globalId: Object.keys(view)[0],
                            }
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
        'listing-choice-component': ListingChoiceComponent,
    }
};