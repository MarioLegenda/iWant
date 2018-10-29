import {SearchBox} from "./SearchBox";
import {Filters} from "./Filters";
import {Sentence} from "./Sentence";
import urlifyFactory from 'urlify';
import {marketplacesList} from "../../../global";
import {SelectedFilters} from "./SelectedFilters";
import {LoadingComponent} from "../LoadingComponent/LoadingComponent";
import {SUPPORTED_SITES} from "../../../global";
import {RepositoryFactory} from "../../../services/repositoryFactory";

export const SearchComponent = {
    data: function() {
        return {
            filtersInitialized: false,
            showSentence: false,
            keyword: null,
            preparedEbaySites: [],
            sitesPrepared: false
        }
    },
    props: ['externalSearchTerm'],
    created() {
        if (!isEmpty(this.externalSearchTerm)) {
            this.submit(this.externalSearchTerm);

            return;
        }

        const splitted = window.location.pathname.split('/');

        if (typeof splitted[2] !== 'undefined') {
            const keyword = splitted[2];
            const replaced = keyword.replace(/-/g, ' ');

            this.onSearchTermChange(replaced);
            this.submit(replaced);
        }
    },
    template: `<div class="AdvancedSearch" id="AdvancedSearchId">
                    <input type="hidden" :value="preparedEbayRequestEvent" />
                    
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
                                        
                    <transition name="fade">
                        <loading-component v-if="searchInitialiseEvent.initialised"></loading-component>
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
            const searchInitialisedEvent = this.$store.state.searchInitialiseEvent;

            if (typeof searchInitialisedEvent === 'object' && searchInitialisedEvent !== null) {
                if (searchInitialisedEvent.initialised === false) {
                    this.sitesPrepared = false;
                }
            }

            return searchInitialisedEvent;
        },

        preparedEbayRequestEvent: function() {
            const preparedSite = this.$store.state.preparedEbayRequestEvent;

            if (preparedSite === null) {
                return this.$store.state.preparedEbayRequestEvent;
            }

            if (!this.preparedEbaySites.includes(preparedSite.preparedData.globalId)) {
                this.preparedEbaySites.push(preparedSite);
            }

            if (!this.sitesPrepared) {
                if (SUPPORTED_SITES.sites.length === this.preparedEbaySites.length) {
                    setTimeout(() => {
                        this.$store.commit('searchInitialiseEvent', {
                            initialised: false,
                            finished: true,
                        });

                        this.$store.commit('preparedEbayRequestEvent', null);

                        this.sitesPrepared = true;
                        this.preparedEbaySites = [];

                        return this.$store.state.preparedEbayRequestEvent;
                    }, 1000);
                }
            }
        }
    },
    methods: {
        onSearchTermChange(searchTerm) {
            this.showSentence = true;

            this.keyword = searchTerm;
        },
        submit(keyword) {
            this.keyword = keyword;

            const urlify = urlifyFactory.create({
                addEToUmlauts: true,
                szToSs: true,
                spaces: "-",
                nonPrintable: "-",
                trim: true
            });

            this.$router.push(`/search/${urlify(this.keyword)}`);

            const model = this.createModel();

            this.$store.commit('searchInitialiseEvent', {
                searchUrl: `/search/${urlify(this.keyword)}`,
                model: model,
                initialised: true,
                finished: false
            });

            const searchRepo = RepositoryFactory.create('search');

            for (const site of SUPPORTED_SITES.sites) {
                const globalId = site.globalId;
                model.globalId = globalId.toUpperCase();


                searchRepo.postPrepareEbaySearch(model, (r) => {
                    this.$store.commit('preparedEbayRequestEvent', {
                        preparedData: r.resource.data,
                        resolved: r.isError
                    });
                });
            }
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