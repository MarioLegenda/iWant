export const LowestPrice = {
    template: `<div class="Filter_Filter-filter">
                  <span
                      @click="addLowestPrice" 
                      class="Filter-simple filter"
                  >Lowest price <i class="fas fa-plus"></i></span> 
               </div>`,
    methods: {
        addLowestPrice() {
            this.$emit('add-lowest-price');
        }
    }
};
