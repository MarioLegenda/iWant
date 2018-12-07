import {HideDuplicateItems} from "../Modifiers/HideDuplicateItems";
import {DoubleLocaleSearch} from "../Modifiers/DoubleLocaleSearch";
import {SAVED_STATE_MODE, SEARCH_TERM} from "../../../store/constants";

export const SearchBox = {
    data: function() {
        return {
            enterToSearch: false,
            text: null,
            isError: false,
            activeClass: 'InputBox',
            errorClass: '',
        }
    },

    created() {
        if (this.getCurrentSearchStateMode === SAVED_STATE_MODE) {
            this.text = this.$store.state.savedSearchStateMode[SEARCH_TERM];
        }
    },

    props: ['externalKeyword'],

    template: `<div class="SearchBoxAdvanced">
                           
                           <div class="SearchBox_InputBox">
                                <input
                                    @input="onInputChange"
                                    v-on:keydown.enter="submit"
                                    type="text" v-model="text"
                                    v-bind:class="[activeClass, errorClass]"
                                    :placeholder="translationsMap.searchInputPlaceholder"/>

                                <div class="SearchBox_SubmitBox">
                                    <button @click="submit"><i class="fas fa-chevron-right"></i></button>
                                </div>
                           </div>
                                                      
                           <p v-if="enterToSearch" class="SearchBoxAdvanced-enter-to-search">* Press Enter to search</p>
                           
                           <div class="ModifiersWrapper">
                               <hide-duplicate-items-modifier></hide-duplicate-items-modifier>
                               <double-locale-search-modifier></double-locale-search-modifier>
                           </div>
               </div>`,

    computed: {
        translationsMap: function() {
            return this.$store.state.translationsMap;
        },

        getCurrentSearchStateMode(state, getters) {
            return this.$store.getters.getCurrentSearchStateMode;
        }
    },

    methods: {
        submit: function() {
            if (this.text === null) {
                this.isError = true;

                this.errorClass = 'Error';
            } else {
                this.errorClass = '';

                this.isError = false;
            }

            this.$emit('submit', this.text);
        },

        onInputChange: function() {
            if (!isEmpty(this.text)) {
                this.enterToSearch = true;
            } else {
                this.enterToSearch = false;
            }

            this.$store.commit('savedSearchStateMode', {
                [SEARCH_TERM]: this.text,
            });

            this.$emit('on-search-term-change', this.text);
        }
    },

    components: {
        'hide-duplicate-items-modifier': HideDuplicateItems,
        'double-locale-search-modifier': DoubleLocaleSearch,
    }
};