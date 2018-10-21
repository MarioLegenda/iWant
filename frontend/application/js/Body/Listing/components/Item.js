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
            width: '100%',
            height: '350px',
            url: null
        }
    },
    created() {
        this.createImage(this.image);
    },
    methods: {
        createImage(image) {
            if (image.url === 'NaN') {
                this.url = '/images/no-image.png';

                return;
            }

            this.url = image.url;
        }
    },
    props: ['image'],
    watch: {
        image: function(newVal, oldVal) {
            if (oldVal.url !== newVal.url) {
                this.createImage(newVal);
            }
        }
    },
    template: `
        <img :src="url" :width="width" :height="height" />
    `
};

const ShopLogoImageItem = {
    data: function() {
        return {
            Ebay: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nibh velit, varius eu mi vel, egestas dictum nisl. Integer fringilla vitae est vel tristique.',
            Etsy: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nibh velit, varius eu mi vel, egestas dictum nisl. Integer fringilla vitae est vel tristique.',
            Amazon: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nibh velit, varius eu mi vel, egestas dictum nisl. Integer fringilla vitae est vel tristique.',
        }
    },
    template: `<img class="ItemTitle-webshop-logo" :src="shopLogo" v-tooltip="{
                    content: hoveredTooltip,
                    placement: 'top-center',
                    offset: 20,
                    classes: ['shop-tooltip'],
                    delay: {
                        show: 100,
                        hide: 100
                    }
               }"/>`,
    computed: {
        shopLogo: function() {
            switch (this.marketplace) {
                case 'Ebay':
                    return '/images/temp_ebay_logo.png';
                case 'Etsy':
                    return '/images/temp_etsy_logo.png';
            }
        },
        hoveredTooltip: function() {
            return this[this.marketplace];
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
    template: `<div v-bind:class="classList">
                   <div v-if="show.taxonomyTitle" class="Item_ItemTaxonomyTitle">
                       <p>{{item.taxonomyName}}</p>
                   </div>
                   
                   <div v-if="show.marketplaceLogo" class="ItemTitle-webshop-logo margin-bottom-20">
                        <shop-logo-image-item v-bind:marketplace="item.marketplace"></shop-logo-image-item>
                   </div>
                   
                   <div class="Item_ItemImage margin-bottom-20">
                       <image-item v-bind:image="item.image"></image-item>
                   </div>
                   
                   <div class="Item_ItemTitle margin-bottom-20">
                        <h1 class="ItemTitle-header">
                            {{ item.title.truncated }}
                        </h1>
                        
                        <p class="ItemTitle-translation"><a v-if="isTranslated" href="http://translate.yandex.com/" target="_blank">Powered by Yandex.Translate</a></p>
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
    computed: {
        isTranslated: function() {
            return this.item.isTranslated;
        }
    },
    props: {
        item: {
            type: Object,
            required: true,
        },
        classList: {
            type: String,
            required: true
        },
        show: {
            type: Object,
            default: function() {
                return {
                    taxonomyTitle: true,
                    marketplaceLogo: true,
                }
            }
        },
    },
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