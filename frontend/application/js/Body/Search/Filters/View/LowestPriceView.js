export const LowestPriceView = {
    template: `<div class="Filter_Filter-filter">
                  <span
                      @click="removeLowestPrice"
                      class="Filter-simple filter added">Lowest price <i class="fas fa-minus"></i>
                  </span> 
               </div>`,
    methods: {
        removeLowestPrice() {
            this.$emit('remove-lowest-price');
        }
    }
};