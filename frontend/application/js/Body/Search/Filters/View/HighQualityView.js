export const HighQualityView = {
    template: `<div class="Filter_Filter-filter">
                  <span
                      @click="removeHighQuality"
                      class="Filter-simple filter added">High quality <i class="fas fa-minus"></i>
                  </span>
               </div>`,
    methods: {
        removeHighQuality() {
            this.$emit('remove-high-quality');
        }
    }
};