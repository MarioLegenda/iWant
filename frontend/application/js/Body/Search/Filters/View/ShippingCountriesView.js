export const ShippingCountriesView = {
    template: `<div class="Filter_Filter-filter">
                  <span
                      @click="removeShippingCountries"
                      class="Filter-select filter added">{{text}}<i class="fas fa-minus"></i>
                  </span>
               </div>`,
    props: ['shippingCountries'],
    computed: {
        text: function() {
            if (this.shippingCountries.length === 1) {
                return `Ships to ${this.shippingCountries[0].name}`
            }

            if (this.shippingCountries.length === 2) {
                return `Ships to ${this.shippingCountries[0].name} and ${this.shippingCountries[1].name}`;
            }

            if (this.shippingCountries.length > 2) {
                const lastItem = this.shippingCountries[this.shippingCountries.length - 1];

                const names = this.shippingCountries.map((v) => {
                    if (v.id !== lastItem.id) {
                        return v.name;
                    }
                });

                let joinedNames = names.join(', ');
                joinedNames = joinedNames.substring(0, joinedNames.length - 2);

                return `Ships to ${joinedNames} and ${lastItem.name}`;
            }
        }
    },
    methods: {
        removeShippingCountries() {
            this.$emit('remove-shipping-countries');
        }
    }
};