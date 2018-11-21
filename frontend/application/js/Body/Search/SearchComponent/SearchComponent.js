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
    props: ['externalSearchTerm'],
    created() {
        if (!isEmpty(this.externalSearchTerm)) {
            this.submit(this.externalSearchTerm);

            return;
        }

        const splitted = window.location.pathname.split('/');

        if (typeof splitted[3] !== 'undefined') {
            const keyword = splitted[3];
            const replaced = keyword.replace(/-/g, ' ');

            this.onSearchTermChange(replaced);
            this.submit(replaced);
        }
    },
    template: `<div class="AdvancedSearch" id="AdvancedSearchId">    
                    <input type="hidden" :value="filtersEvent" />
                                    
                    <search-box
                        v-bind:external-keyword="keyword"
                        v-on:submit="submit"
                        v-on:on-search-term-change="onSearchTermChange">
                    </search-box>
                    
                    <selected-filters></selected-filters>
                             
                    <transition name="fade">
                        <sentence
                           v-if="showSentence"
                           v-bind:sentenceData="sentenceData"
                           v-bind:showSentence="showSentence">
                        </sentence>
                    </transition>
                    
               </div>`,
    watch: {
        externalSearchTerm: function(newVal, oldVal) {
            if (newVal === oldVal) {
                return null;
            }

            this.onSearchTermChange(newVal);
            this.submit(newVal);
        }
    },

    computed: {
        sentenceData: function() {
            return {
                filters: this.filters,
                keyword: this.keyword,
            }
        },

        filtersEvent: function() {
            return this.$store.state.filtersEvent;
        },

        searchInitialiseEvent: function() {
            return this.$store.state.searchInitialiseEvent;
        },
    },

    methods: {
        onSearchTermChange(searchTerm) {
            if (isEmpty(searchTerm)) {
                if (this.searchInitialiseEvent.initialised === true) {
                    this.$store.commit('searchInitialiseEvent', {
                        searchUrl: null,
                        model: null,
                        initialised: false,
                    });
                }

                this.$store.commit('ebaySearchListing', null);

                this.showSentence = false;

                return false;
            }

            this.showSentence = true;

            this.keyword = searchTerm;

            this.$store.commit('searchInitialiseEvent', {
                searchUrl: null,
                model: null,
                initialised: false,
            });

            this.$store.commit('ebaySearchListingLoading', false);

            this.$store.commit('ebaySearchListing', null);
        },

        submit(keyword) {
            if (isEmpty(keyword)) {
                if (this.searchInitialiseEvent.initialised === true) {
                    this.$store.commit('searchInitialiseEvent', {
                        searchUrl: null,
                        model: null,
                        initialised: false,
                    });
                }

                this.$store.commit('ebaySearchListing', null);

                return false;
            }

            this.$store.commit('ebaySearchListing', {
                siteInformation: null,
                items: null
            });

            this.keyword = keyword;

            const urlify = urlifyFactory.create({
                addEToUmlauts: true,
                szToSs: true,
                spaces: "-",
                nonPrintable: "-",
                trim: true
            });

            this.$router.push(`/${this.$localeInfo.locale}/search/${urlify(this.keyword)}`);

            const model = this.createModel();

            this.$store.commit('searchInitialiseEvent', {
                searchUrl: `/search/${urlify(this.keyword)}`,
                model: model,
                initialised: true,
            });
        },

        determineMarketplaces(model) {
            let marketplaces = {};

            if (model.filters.marketplaces.length === 0) {
                marketplaces = marketplacesList;
            }

            if (model.filters.marketplaces.length > 0) {
                const marketplaceFilters = model.filters.marketplaces;

                for (const m of marketplaceFilters) {
                    marketplaces[m.normalized] = m.name;
                }
            }

            return marketplaces;
        },

        createModel() {
            return {
                keyword: this.keyword,
                filters: this.filtersEvent,
                pagination: {
                    limit: 8,
                    page: 1,
                },
                locale: this.$localeInfo.locale,
                internalPagination: {
                    limit: 80,
                    page: 1
                },
                range: {
                    to: 1,
                    from: 1,
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