export const SingleAddFilter = {
    template: `<div class="Filter_Filter-filter">
                  <span
                      @click="addFilter" 
                      class="Filter-simple filter"
                  >{{filterText}}<i class="fas fa-plus"></i></span> 
               </div>`,
    props: ['eventName', 'filterText'],
    methods: {
        addFilter() {
            this.$emit(this.eventName);
        }
    }
};