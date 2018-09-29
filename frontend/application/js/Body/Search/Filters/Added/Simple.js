export const Simple = {
    template: `<div class="Filter_Filter-filter">
                  <span v-on:click="onRemoveSimpleFilter" class="Filter-simple filter added">{{data.text}} <i class="fas fa-minus"></i></span> 
               </div>`,
    props: ['data'],
    methods: {
        onRemoveSimpleFilter() {
            this.$emit('remove-simple-filter', this.data);
        }
    }
};