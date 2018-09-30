export const MarketplaceView = {
    template: `<div class="Filter_Filter-filter">
                  <span
                      @click="removeMarketplaces"
                      class="Filter-select filter added">{{text}}<i class="fas fa-minus"></i>
                  </span>
               </div>`,
    props: ['marketplaces'],
    computed: {
        text: function() {
            if (this.marketplaces.length === 1) {
                return `Ships to ${this.marketplaces[0].name}`
            }

            if (this.marketplaces.length === 2) {
                return `Ships to ${this.marketplaces[0].name} and ${this.marketplaces[1].name}`;
            }

            if (this.marketplaces.length > 2) {
                const lastItem = this.marketplaces[this.marketplaces.length - 1];

                const names = this.marketplaces.map((v) => {
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
        removeMarketplaces() {
            this.$emit('remove-marketplaces');
        }
    }
};