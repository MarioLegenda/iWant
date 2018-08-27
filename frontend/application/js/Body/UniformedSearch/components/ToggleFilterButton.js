export const ToggleFilterButton = {
    data: function() {
        return {
            showFiltersButtonToggle: false
        };
    },
    template: `<div class="wrap toggle-filter-box-wrapper">
                   <div class="toggle-filter-box-fixed-width-row">
                       <button v-on:click="toggleFilterBox">
                           Add filters
                           <i v-bind:class="toggleFilterClass"></i>
                        </button>
                   </div>
               </div>`,
    computed: {
        toggleFilterClass: function () {
            return {
                'fas fa-angle-right': this.showFiltersButtonToggle === false,
                'fas fa-angle-up': this.showFiltersButtonToggle === true
            }
        }
    },
    methods: {
        toggleFilterBox: function() {
            this.showFiltersButtonToggle = !this.showFiltersButtonToggle;

            this.$emit('toggle-filter-box', this.showFiltersButtonToggle);
        }
    },
};