import {SearchComponent} from "./SearchComponent/SearchComponent";
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
    beforeDestroy() {
        this.$store.commit('searchInitialiseEvent', {
            searchUrl: null,
            initialised: false,
        });

        this.$store.commit('ebaySearchListing', {
            siteInformation: null,
            items: null
        });

        this.$store.commit('ebaySearchListingLoading', false);

        this.$store.commit('filtersEvent', this.$defaultFilters);

        this.$store.commit('listingInitialiseEvent', {
            initialised: false,
        });
    },
    template: `<transition name="fade">
                
                <div id="search_page">         
                    <filters></filters>
                    
                    <div class="LeftPanel">
                        <search-component
                            v-bind:external-search-term="searchTerm">
                        </search-component>
                        
                        <transition name="fade">
                            <listing-choice-component v-if="isSearchInitialised"></listing-choice-component>
                        </transition>
                    
                        <listing-component></listing-component>
                    </div>
                    
               </div>
               
               </transition>`,
    computed: {
        searchTerm: function() {
            return this.$store.state.searchTerm;
        },
        isSearchInitialised() {
            return this.$store.getters.isSearchInitialised;
        }
    },
    components: {
        'listing-component': ListingComponent,
        'search-component': SearchComponent,
        'filters': Filters,
        'listing-choice-component': ListingChoiceComponent,
    }
};