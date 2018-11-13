export const Price = {
    template: `
        <p v-if="currency === 'USD'" class="Price"><i v-bind:class="decideClass()"></i> {{price}}</p>
        <p v-else-if="currency === 'EUR'" class="Price"><i v-bind:class="decideClass()"></i> {{price}}</p>
        <p v-else-if="currency === 'GBP'" class="Price"><i v-bind:class="decideClass()"></i> {{price}}</p>
        <p v-else class="Price"><i class="currencySign">{{currency}}</i>{{price}}</p>
    `,
    props: ['price', 'currency'],
    methods: {
        decideClass() {
            if (this.currency === 'USD') {
                return 'currencySign fas fa-dollar-sign';
            } else if (this.currency === 'EUR') {
                return 'currencySign fas fa-euro-sign';
            } else if (this.currency === 'GBP') {
                return 'currencySign fas fa-pound-sign';
            }
        }
    },
};