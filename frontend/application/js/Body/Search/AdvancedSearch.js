import {SearchBoxAdvanced} from "./SearchBoxAdvanced";
import {Filters} from "./Filters";
import {Sentence} from "./Sentence";
import urlifyFactory from 'urlify';
import {FoundProductsInformation} from "./FoundProductsInformation";

export const AdvancedSearch = {
    data: function() {
        return {
            filtersInitialized: false,
            showSentence: false,
            keyword: null,
            filters: {
                lowestPrice: false,
                highestPrice: false,
                highQuality: false,
                shippingCountries: [],
                marketplaces: [],
                taxonomies: [],
                globalIds: [],
            }
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
                    <search-box-advanced
                        v-bind:external-keyword="keyword"
                        v-on:submit="submit"
                        v-on:on-search-term-change="onSearchTermChange">
                    </search-box-advanced>
                    
                    <sentence
                        v-if="showSentence"
                        v-bind:sentenceData="sentenceData"
                        v-bind:showSentence="showSentence">
                    </sentence>
                    
                    <filters
                        v-on:add-filter="addFilter">
                    </filters>
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
        }
    },
    methods: {
        addFilter(filter) {
            this.showSentence = true;

            if (!this.filters.hasOwnProperty(filter.name)) {
                throw new Error(`Filter ${filter.name} not found`);
            }

            this.filters[filter.name] = filter.value;
        },
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

            this.$store.commit('foundSearchProducts', {
                ebay: false,
                etsy: false,
            });

            this.$emit('get-ebay-items', this.createModel());
            this.$emit('get-etsy-items', this.createModel());
        },
        createModel() {
            return {
                keyword: this.keyword,
                filters: this.filters,
                pagination: {
                    limit: 4,
                    page: 1,
                },
                viewType: 'globalIdView',
            }
        }
    },
    components: {
        'search-box-advanced': SearchBoxAdvanced,
        'filters': Filters,
        'sentence': Sentence,
    }
};