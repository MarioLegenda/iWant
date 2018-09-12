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

export const Item = {
    template: `<div class="Item">
                   <div class="ItemTitle-webshop-logo margin-bottom-10">
                       <img class="ItemTitle-webshop-logo" src="/images/temp_ebay_logo.png"/>
                   </div>
                   
                   <div class="Item_ItemImage margin-bottom-10">
                       <image-item v-bind:image="item.image"></image-item>
                   </div>
                   
                   <div class="Item_ItemTitle margin-bottom-10">
                        <h1 class="ItemTitle-header">
                            {{ item.title.truncated }}
                        </h1>
                   </div>
                   
                   <div class="Item_Details">
                       <a href="" class="Item_Details-shop-name margin-bottom-10">{{ item.shopName }}</a>
                       <p class="Item_Details-item-price margin-bottom-10"><span><currency-item v-bind:currency="item.price.currency"></currency-item></span> {{ item.price.price }}</p>
                   </div>
                   
                   <div class="Item_ItemLink">
                       <a :href="item.viewItemUrl" target="__blank" class="ItemLink-closer-look">Take a closer look</a>
                   </div>
               </div>`,
    props: ['item'],
    components: {
        'currency-item': CurrencyItem,
        'image-item': ImageItem,
    }
};