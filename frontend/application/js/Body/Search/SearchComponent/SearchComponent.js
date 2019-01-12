import {SearchBox} from "./SearchBox";
import {Filters} from "./Filters";
import {Sentence} from "./Sentence";
import urlifyFactory from 'urlify';
import {SelectedFilters} from "./SelectedFilters";
import {LoadingComponent} from "../LoadingComponent/LoadingComponent";

export const SearchComponent = {
    data: function() {
        return {
            filtersInitialized: false,
            showSentence: false,
            keyword: null,
            preparedEbaySites: [],
        }
    },
    template: `<div class="AdvancedSearch" id="AdvancedSearchId">                                    
                    <search-box
                        v-on:submit="submit"
                        v-on:on-search-term-change="onSearchTermChange">
                    </search-box>
                                        
                    <selected-filters v-if="areSingleAddFiltersSelected"></selected-filters>
                                                                     
<!--                    <transition name="fade">
                        <sentence
                           v-if="showSentence"
                           v-bind:sentenceData="sentenceData"
                           v-bind:showSentence="showSentence">
                        </sentence>
                    </transition>-->
                    
               </div>`,
    computed: {
        sentenceData: function() {
            return {
                filters: this.filters,
                keyword: this.keyword,
            }
        },

        getFilters() {
            return this.$store.getters.getFilters;
        },

        areSingleAddFiltersSelected() {
            return this.$store.getters.areSingleAddFiltersSelected;
        }
    },

    methods: {
        onSearchTermChange(searchTerm) {
            if (isEmpty(searchTerm)) {
                this.$store.dispatch('destroyEntireState');

                this.showSentence = false;

                return false;
            }

            this.$store.commit('searchInitialiseEvent', {
                initialised: false,
            });

            this.$store.commit('listingInitialiseEvent', {
                initialised: false,
            });

            this.$store.commit('ebaySearchListing', {
                siteInformation: null,
                items: null,
            });

            this.$store.commit('totalListing', null);

            this.showSentence = true;

            this.keyword = searchTerm;
        },

        submit(keyword) {
            if (isEmpty(keyword)) {
                this.$store.dispatch('destroyEntireState');

                return false;
            }

            this.keyword = keyword;

            const urlify = urlifyFactory.create({
                addEToUmlauts: true,
                szToSs: true,
                spaces: "-",
                nonPrintable: "-",
                trim: true
            });

            const model = this.createModel();

            this.$store.commit('ebaySearchListing', {
                siteInformation: null,
                items: null,
            });

            this.$store.commit('totalListing', null);

            this.$store.commit('modelWasCreated', model);
            this.$store.commit('modelWasUpdated', model);

            this.$store.commit('searchInitialiseEvent', {
                searchUrl: `/search/${urlify(this.keyword)}`,
                initialised: true,
            });

            setTimeout(() => scrollToElement(document.getElementById('ListingChoiceComponentId'), 200));
        },

        createModel() {
            return {
                keyword: this.keyword,
                filters: this.getFilters,
                pagination: {
                    limit: 8,
                    page: 1,
                },
                locale: this.$localeInfo.locale,
                internalPagination: {
                    limit: 80,
                    page: 1
                },
                globalId: null,
            }
        }
    },
    components: {
        'search-box': SearchBox,
        'filters': Filters,
        'sentence': Sentence,
        'selected-filters': SelectedFilters,
        'loading-component': LoadingComponent,
    }
};