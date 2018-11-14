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
    template: `<transition name="fade"><div id="search_page">
                    <input type="hidden" :input="searchInitialiseEvent" />
         
                    <filters></filters>
                    
                    <div class="LeftPanel">
                        <search-component
                            v-bind:external-search-term="searchTerm">
                        </search-component>
                        
                        <transition name="fade">
                            <listing-choice-component></listing-choice-component>
                        </transition>
                    
                        <listing-component></listing-component>
                    </div>
               </div></transition>`,
    computed: {
        searchTerm: function() {
            return this.$store.state.searchTerm;
        },

        searchInitialiseEvent: function() {
            return this.$store.state.searchInitialiseEvent;
        },
    },
    components: {
        'listing-component': ListingComponent,
        'search-component': SearchComponent,
        'filters': Filters,
        'listing-choice-component': ListingChoiceComponent,
    }
};