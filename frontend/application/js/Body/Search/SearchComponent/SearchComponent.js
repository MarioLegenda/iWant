import {SearchBox} from "./SearchBox";
import {Filters} from "./Filters";
import {Sentence} from "./Sentence";
import urlifyFactory from 'urlify';
import {marketplacesList} from "../../../global";
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
            this.showSentence = true;

            this.keyword = searchTerm;
        },

        submit(keyword) {
            this.$store.commit('ebaySearchListing', null);

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
                finished: false
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

            // DO NOT FORGET TO REMOVE THIS IF THE USER WILL HAVE THE POSSIBILITY TO CHOOSE
            // A BEST MATCH FILTERS OPTION

            let filters = this.filtersEvent;

            if (!filters.highestPrice) {
                filters.bestMatch = true;
            }

            return {
                keyword: this.keyword,
                filters: filters,
                pagination: {
                    limit: 80,
                    page: 1,
                },
                viewType: 'globalIdView',
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