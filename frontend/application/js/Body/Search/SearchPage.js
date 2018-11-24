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
        this.$store.dispatch('destroyEntireState');
    },
    created() {
        this.$store.subscribe((mutation, state) => {
            if (mutation.type === 'filtersEvent') {
                let modelCopy = Object.assign({}, this.getModel);
                modelCopy.filters = state.filtersEvent;

                if (modelCopy.filters.lowestPrice) {
                    if (this.isListingInitialised) {
                        modelCopy.range = {
                            to: 1,
                            from: this.getTotalListings.length
                        }
                    }
                }

                this.$store.commit('modelWasUpdated', modelCopy);
            }
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
        },
        isListingInitialised() {
            return this.$store.getters.isListingInitialised;
        },
        getModel() {
            return this.$store.getters.getModel;
        },
        getTotalListings() {
            return this.$store.getters.getTotalListings;
        }
    },
    components: {
        'listing-component': ListingComponent,
        'search-component': SearchComponent,
        'filters': Filters,
        'listing-choice-component': ListingChoiceComponent,
    }
};