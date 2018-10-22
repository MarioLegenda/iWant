export const MarketplaceChoice = {
    data: function() {
        return {
            marketplaces: {
                ebay: false,
                etsy: false,
            },
            ebayImg: '/images/temp_ebay_logo.png',
            etsyImg: '/images/temp_etsy_logo.png',
        }
    },
    template: `
            <div class="MarketplaceChoice">
                <div class="ChoiceButtons">
                    <h1>Choose your marketplace</h1>
                    
                    <div class="ChoiceCaret">
                        <i class="fas fa-long-arrow-alt-down"></i>
                    </div>
                    
                    <div class="ButtonWrapper">
                        <p v-bind:class="{'ClickableElement-highlighted': marketplaces.ebay }" class="ClickableElement" @click="show('ebay')"><img alt="Choose eBay" :src="ebayImg" /></p>
                        <p v-bind:class="{'ClickableElement-highlighted': marketplaces.etsy }" class="ClickableElement" @click="show('etsy')"><img alt="Choose Etsy" :src="etsyImg" /></p>
                    </div>
                </div>
            </div>
    `,
    methods: {
        show(marketplace) {
            for (let marketplace in this.marketplaces) {
                if (this.marketplaces.hasOwnProperty(marketplace)) {
                    this.marketplaces[marketplace] = false;
                }
            }

            this.marketplaces[marketplace] = !this.marketplaces[marketplace];

            this.$emit('on-choice', marketplace);
        }
    }
};