export const SingleAddFilter = {
    template: `<div class="SingleFilterWrapper">
                  <p
                      @click="addFilter" 
                      class="SingleFilter"
                  >{{filterText}}<i class="fas fa-plus"></i></p> 
               </div>`,
    props: ['eventName', 'filterText'],
    methods: {
        addFilter() {
            this.$emit(this.eventName);
        }
    }
};