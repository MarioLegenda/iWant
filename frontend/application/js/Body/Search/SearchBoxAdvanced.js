export const SearchBoxAdvanced = {
    data: function() {
        return {
            text: null,
            isError: false,
            activeClass: 'InputBox',
            errorClass: '',
        }
    },
    template: `<div class="SearchBoxAdvanced">
                           <div class="SearchBox_InputBox">
                                <input type="text" v-model="text" v-bind:class="[activeClass, errorClass]" placeholder="what would you like?" />
                           </div>
                           
                           <div class="SearchBox_SubmitBox">
                                <button @click="submit"><i class="fas fa-chevron-right"></i></button>
                           </div>
               </div>`,
    methods: {
        submit: function() {
            if (this.text === null) {
                this.isError = true;

                this.errorClass = 'Error';
            } else {
                this.errorClass = '';

                this.isError = false;
            }

            this.$emit('submit');
        }
    }
};