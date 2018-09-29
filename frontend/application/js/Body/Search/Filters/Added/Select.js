export const Select = {
    data: function() {
        return {
            filterText: ''
        }
    },
    template: `<div class="Filter_Filter-filter">
                  <span v-on:click="onRemoveSimpleFilter" class="Filter-select filter added">{{filterText}} <i class="fas fa-minus"></i></span> 
               </div>`,
    props: ['data'],
    created() {
        this.createCountriesView(this.data.values);
    },
    watch: {
        data: function(newVal, oldVal) {
            this.createCountriesView(newVal.values);
        }
    },
    methods: {
        onRemoveSimpleFilter() {
            this.filterText = '';

            this.$emit('remove-simple-filter', this.data);
        },
        createCountriesView(countries) {
            if (countries[0] === 'worldwide') {
                this.filterText = 'Ships worldwide';

                return false;
            }

            const names = countries.map((v) => {
                return v.name;
            });

            if (names.length === 1) {
                this.filterText = `Shipping to ${names[0]}`;
            }

            if (names.length === 2) {
                this.filterText = `Shipping to ${names[0]} and ${names[1]}`;
            }

            if (names.length > 2) {
                const lastName = names.splice(names.length - 1, 1);

                this.filterText = `Shipping to ${names.join(', ')} and ${lastName}`;
            }
        }
    },
};