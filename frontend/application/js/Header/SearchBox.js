import urlifyFactory from 'urlify';

export const SearchBox = {
    data: function() {
        return {
            enterToSearch: false,
            term: null
        }
    },
    created() {
        this.term = null;
    },
    template: `<div class="SearchBox">
                           <div class="SearchBox_InputBox">
                                <input @input="onInputChange" v-on:keydown.enter="submit" type="text" v-model="term" placeholder="what would you like?" />
                           </div>
                           
                           <div class="SearchBox_SubmitBox">
                                <button><i class="fas fa-chevron-right"></i></button>
                           </div>
                           
                           <p v-if="enterToSearch" class="SearchBox-enter-to-search">* Press Enter to search</p>
                           
                           <div class="SearchBox_AdvancedSearch">
                               <router-link to="/search">Advanced search <i class="fas fa-search"></i></router-link>
                           </div>
               </div>`,
    methods: {
        onInputChange() {
            if (!isEmpty(this.term)) {
                this.enterToSearch = true;
            } else {
                this.enterToSearch = false;
            }
        }
    }
};