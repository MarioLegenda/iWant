export const Item = {
    template: `<div class="Item">
                   <div class="ItemTitle-webshop-logo margin-bottom-10">
                       <img class="ItemTitle-webshop-logo" src="/images/temp_ebay_logo.png"/>
                   </div>
                   
                   <div class="Item_ItemImage margin-bottom-10">
                       <img :src="item.imageUrl" />
                   </div>
                   
                   <div class="Item_ItemTitle margin-bottom-10">
                        <h1 class="ItemTitle-header">
                            {{ item.title }}
                        </h1>
                   </div>
                   
                   <div class="Item_Details">
                       <a href="" class="Item_Details-shop-name margin-bottom-10">{{ item.shopName }}</a>
                       <p class="Item_Details-item-price margin-bottom-10"><span>$</span>{{ item.price }}</p>
                   </div>
                   
                   <div class="Item_ItemLink">
                       <a :href="item.viewItemUrl" target="__blank" class="ItemLink-closer-look">Take a closer look</a>
                   </div>
               </div>`,
    props: ['item'],
};