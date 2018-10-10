export const SearchBoxAdvanced = {
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
        if (!isEmpty(this.externalKeyword)) {
            this.text = this.externalKeyword;
        }
    },
    props: ['externalKeyword'],
    template: `<div class="SearchBoxAdvanced">
                           <transition name="fade">
                               <p v-if="searchLoading.searchProgress" class="LoadingBar">
                                   Searching
                                   <span v-bind:class="{ active: searchLoading.ebay }">Ebay</span> |
                                   <span>Etsy</span> |
                                   <span>Amazon</span>
                                
                                   <i class="spinner fas fa-circle-notch fa-spin"></i>
                               </p>
                           </transition>
                           
                           <div class="SearchBox_InputBox">
                                <input 
                                    @input="onInputChange" 
                                    v-on:keydown.enter="submit" 
                                    type="text" v-model="text" 
                                    v-bind:class="[activeClass, errorClass]" 
                                    placeholder="what would you like?"/>

                           </div>
                           
                           <div class="SearchBox_SubmitBox">
                                <button @click="submit"><i class="fas fa-chevron-right"></i></button>
                           </div>
                                                      
                           <p v-if="enterToSearch" class="SearchBoxAdvanced-enter-to-search">* Press Enter to search</p>
               </div>`,
    computed: {
        searchLoading: function() {
            return this.$store.state.searchLoading;
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

            this.$emit('on-search-term-change', this.text);
        }
    }
};