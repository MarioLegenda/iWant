export const SingleAddFilter = {
    template: `<div class="Filter_Filter-filter">
                  <p
                      @click="addFilter" 
                      class="Filter-simple filter"
                  >{{filterText}}<i class="fas fa-plus"></i></p> 
               </div>`,
    props: ['eventName', 'filterText'],
    methods: {
        addFilter() {
            this.$emit(this.eventName);
        }
    }
};