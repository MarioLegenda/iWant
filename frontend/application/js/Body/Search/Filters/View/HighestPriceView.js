export const HighestPriceView = {
    template: `<div class="Filter_Filter-filter">
                  <span
                      @click="removeHighestPrice"
                      class="Filter-simple filter added">Highest price <i class="fas fa-minus"></i>
                  </span>
               </div>`,
    methods: {
        removeHighestPrice() {
            this.$emit('remove-highest-price');
        }
    }
};