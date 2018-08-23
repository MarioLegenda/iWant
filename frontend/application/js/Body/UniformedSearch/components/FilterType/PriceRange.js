export const PriceRange = {
    data: function() {
        return {
            minPrice: null,
            maxPrice: null,
        }
    },
    template: `
        <div class="price-range-filter">
            <span>Price from </span>
            <input type="text" v-on:input="onMinPrice"/>
            <span>to</span>
            <input type="text" v-on:input="onMaxPrice"/>
        </div>
    `,
    props: ['filterData'],
    methods: {
        onMinPrice: function(e) {
            const val = e.target.value;

            if (val === '') {
                this.minPrice = null;
            } else {
                this.minPrice = val;
            }

            this.$emit('price-range-update', {
                id: this.filterData.id,
                minPrice: this.minPrice,
                maxPrice: this.maxPrice,
            })
        },
        onMaxPrice: function(e) {
            const val = e.target.value;

            if (val === '') {
                this.maxPrice = null;
            } else {
                this.maxPrice = val;
            }


            this.$emit('price-range-update', {
                id: this.filterData.id,
                minPrice: this.minPrice,
                maxPrice: this.maxPrice,
            })
        }
    }
};