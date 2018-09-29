export const HighestPrice = {
    template: `<div class="Filter_Filter-filter">
                  <span 
                      v-on:click="addHighestPrice" 
                      class="Filter-simple filter"
                  >Highest price <i class="fas fa-plus"></i></span> 
               </div>`,
    methods: {
        addHighestPrice() {
            this.$emit('add-highest-price');
        }
    }
};