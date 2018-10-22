export const SingleAddView = {
    template: `<div class="Filter_Filter-filter">
                  <p
                      @click="removeFilter"
                      class="Filter-simple filter added">{{filterText}}<i class="fas fa-minus"></i>
                  </p>
               </div>`,
    props: ['eventName', 'filterText'],
    methods: {
        removeFilter() {
            this.$emit(this.eventName);
        }
    }
};