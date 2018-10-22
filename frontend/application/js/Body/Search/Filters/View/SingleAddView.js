export const SingleAddView = {
    template: `<div class="Filter_Filter-filter">
                  <span
                      @click="removeFilter"
                      class="Filter-simple filter added">{{filterText}}<i class="fas fa-minus"></i>
                  </span>
               </div>`,
    props: ['eventName', 'filterText'],
    methods: {
        removeFilter() {
            this.$emit(this.eventName);
        }
    }
};