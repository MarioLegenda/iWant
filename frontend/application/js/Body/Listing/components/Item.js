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
            width: '120px',
            height: '120px',
            url: null
        }
    },
    created() {
        if (this.image.url === 'NaN') {
            this.url = '/images/no-image.png';

            return;
        }

        this.url = this.image.url;
        this.width = this.image.width;
        this.height = this.image.height;
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

export const Item = {
    template: `<div class="Item">
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
                       <a href="" class="Item_Details-shop-name margin-bottom-10">{{ item.shopName }}</a>
                       <p class="Item_Details-item-price margin-bottom-10"><span><currency-item v-bind:currency="item.price.currency"></currency-item></span> {{ item.price.price }}</p>
                   </div>
                   
                   <div class="Item_ItemLink margin-bottom-20">
                       <a :href="item.viewItemUrl" target="__blank" class="ItemLink-closer-look">Take a closer look</a>
                   </div>
               </div>`,
    props: ['item'],
    created() {
        console.log(this.item);
    },
    components: {
        'currency-item': CurrencyItem,
        'image-item': ImageItem,
        'shop-logo-image-item': ShopLogoImageItem,
    }
};