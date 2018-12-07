import {SearchComponent} from "./SearchComponent/SearchComponent";
import {ListingComponent} from "./ListingComponent/ListingComponent";
import {Filters} from "./SearchComponent/Filters";
import {ListingChoiceComponent} from "./ListingChoiceComponent/ListingChoiceComponent";
import {SAVED_STATE_MODE, STATE_RESTORE_MODE, RESTORING_STATE_MODE} from "../../store/constants";

export const SearchPage = {
    data: function() {
        return {
            currentEbayGlobalId: null,
            ebayHttpInProgress: false,
        }
    },

    beforeDestroy() {
        if (!isEmpty(this.getTotalListings)) {
            this.$store.dispatch('saveSearchState');

            return;
        }

        this.$store.dispatch('destroyEntireState');
    },

    created() {
        if (this.getCurrentSearchStateMode === SAVED_STATE_MODE) {
            this.$store.dispatch('restoreSearchState');
        }

        this.$store.subscribe((mutation, state) => {
            if (mutation.type === 'filtersEvent') {
                let modelCopy = Object.assign({}, this.getModel);
                modelCopy.filters = state.filtersEvent;

                this.$store.commit('modelWasUpdated', modelCopy);
            }
        });
    },

    template: `<transition name="fade">
                
                <div id="search_page">     
                    <filters></filters>
                    
                    <div class="LeftPanel">
                        <search-component>
                        </search-component>
                        
                        <transition name="fade">
                            <listing-choice-component v-if="isSearchInitialised"></listing-choice-component>
                        </transition>
                    
                        <listing-component></listing-component>
                    </div>
                    
               </div>
               
               </transition>`,

    computed: {
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
        },

        getCurrentSearchStateMode(state, getters) {
            return this.$store.getters.getCurrentSearchStateMode;
        }
    },

    components: {
        'listing-component': ListingComponent,
        'search-component': SearchComponent,
        'filters': Filters,
        'listing-choice-component': ListingChoiceComponent,
    }
};