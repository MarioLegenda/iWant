export const TaxonomyView = {
    template: `<div class="Filter_Filter-filter">
                  <span
                      @click="removeTaxonomies"
                      class="Filter-select filter added">{{text}}<i class="fas fa-minus"></i>
                  </span>
               </div>`,
    props: ['taxonomies'],
    computed: {
        text: function() {
            if (this.taxonomies.length === 1) {
                return `Search in ${this.taxonomies[0].name}`
            }

            if (this.taxonomies.length === 2) {
                return `Search in ${this.taxonomies[0].name} and ${this.taxonomies[1].name}`;
            }

            if (this.taxonomies.length > 2) {
                const lastItem = this.taxonomies[this.taxonomies.length - 1];

                const names = this.taxonomies.map((v) => {
                    if (v.id !== lastItem.id) {
                        return v.name;
                    }
                });

                let joinedNames = names.join(', ');
                joinedNames = joinedNames.substring(0, joinedNames.length - 2);

                return `Search in ${joinedNames} and ${lastItem.name}`;
            }
        }
    },
    methods: {
        removeTaxonomies() {
            this.$emit('remove-taxonomies');
        }
    }
};