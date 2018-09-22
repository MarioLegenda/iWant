const CurrencyItem = {
    template: `
        <i v-if="currency === 'USD'" class="fas fa-dollar-sign"></i>
        <i v-else-if="currency === 'GBP'" class="fas fa-pound-sign"></i>
        <i v-else-if="currency === 'EUR'" class="fas fa-euro-sign"></i>
        <i v-else>{{currency}}</i>
    `,
    props: ['currency']
};

const ImageItem = {
    data: function() {
        return {
            width: '140px',
            height: '140px',
            url: null
        }
    },
    created() {
        if (this.image.url === 'NaN') {
            this.url = '/images/no-image.png';

            return;
        }

        this.url = this.image.url;

        if (!this.image.width > 140) {
            this.width = this.image.width;
        }

        if (!this.image.height > 140) {
            this.height = this.image.height;
        }
    },
    props: ['image'],
    template: `
        <img :src="url" :width="width" :height="height" />
    `
};

const ShopLogoImageItem = {
    template: `<img class="ItemTitle-webshop-logo" :src="shopLogo"/>`,
    computed: {
        shopLogo: function() {
            switch (this.marketplace) {
                case 'Ebay':
                    return '/images/temp_ebay_logo.png';
                case 'Etsy':
                    return '/images/temp_etsy_logo.png';
            }
        }
    },
    props: ['marketplace'],
};

const ShippingCountriesPopupItem = {
    template: `<v-popover offset="16">
                    <button class="tooltip-target b3">View shipping locations</button>
                    
                    <template slot="popover">
                        <div class="ShippingListPopover">
                            <p v-for="(item, index) in shippingLocations" :key="index"><span>{{item.name}}</span> <img :src="item.flag"/></p>
                        </div>
                    </template>
               </v-popover>`,
    props: ['shippingLocations']
};

const ShippingCountriesListItem = {
    template: `
                   <p v-if="worldwide">Ships worldwide</p>
                   <div v-else class="Item_Details-shipping-list"><shipping-countries-list-popover v-bind:shippingLocations="shippingLocations"></shipping-countries-list-popover></div>
               `,
    computed: {
        worldwide: function() {
            if (this.shippingLocations[0] === 'Worldwide') {
                return true;
            }
        }
    },
    props: ['shippingLocations'],
    components: {
        'shipping-countries-list-popover': ShippingCountriesPopupItem,
    }
};

export const Item = {
    template: `<div class="Item">
                   <div class="Item_ItemTaxonomyTitle">
                       <p>{{item.taxonomyName}}</p>
                   </div>
                   
                   <div class="ItemTitle-webshop-logo margin-bottom-20">
                        <shop-logo-image-item v-bind:marketplace="item.marketplace"></shop-logo-image-item>
                   </div>
                   
                   <div class="Item_ItemImage margin-bottom-20">
                       <image-item v-bind:image="item.image"></image-item>
                   </div>
                   
                   <div class="Item_ItemTitle margin-bottom-20">
                        <h1 class="ItemTitle-header">
                            {{ item.title.truncated }}
                        </h1>
                   </div>
                   
                   <div class="Item_Details margin-bottom-20">
                       <div class="Item_Details-shipping-countries-list margin-bottom-20">
                           <shipping-list-countries-item v-bind:shippingLocations="item.shippingLocations"></shipping-list-countries-item>
                       </div>
                       
                       <p class="Item_Details-item-price margin-bottom-10">
                            <span>
                                <currency-item v-bind:currency="item.price.currency"></currency-item>
                            </span> {{ item.price.price }}
                            from <a href="" class="Item_Details-shop-name margin-bottom-10">{{ item.shopName }}</a>
                       </p>
                   </div>
                   
                   <div class="Item_ItemLink margin-bottom-20">
                       <router-link :to="item.staticUrl" class="ItemLink-closer-look" v-on:click.native="saveItemToStore">Take a closer look</router-link>
                   </div>
               </div>`,
    props: ['item'],
    methods: {
        saveItemToStore: function() {
            this.$store.commit('singleItem', this.item);
        }
    },
    components: {
        'currency-item': CurrencyItem,
        'image-item': ImageItem,
        'shop-logo-image-item': ShopLogoImageItem,
        'shipping-list-countries-item': ShippingCountriesListItem
    }
};