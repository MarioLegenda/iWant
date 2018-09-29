export const HighQuality = {
    template: `<div class="Filter_Filter-filter">
                  <span
                      @click="addHighQuality" 
                      class="Filter-simple filter"
                  >High quality <i class="fas fa-plus"></i></span> 
               </div>`,
    methods: {
        addHighQuality() {
            this.$emit('add-high-quality');
        }
    }
};