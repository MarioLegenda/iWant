export const Simple = {
    template: `<div class="Filter_Filter-filter">
                  <span v-on:click="onAddSimpleFilter" class="Filter-simple filter">{{data.text}} <i class="fas fa-plus"></i></span> 
               </div>`,
    props: ['data'],
    methods: {
        onAddSimpleFilter() {
            this.$emit('add-simple-filter', this.data);
        }
    }
};