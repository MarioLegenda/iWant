export const Item = {
    template: `<div class="Item">
                   <div class="ItemTitle-webshop-logo margin-bottom-10">
                       <img class="ItemTitle-webshop-logo" src="/images/temp_ebay_logo.png"/>
                   </div>
                   
                   <div class="Item_ItemImage margin-bottom-10">
                       <img src="/images/start_page_background.jpg" />
                   </div>
                   
                   <div class="Item_ItemTitle margin-bottom-10">
                        <h1 class="ItemTitle-header">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s
                        </h1>
                   </div>
                   
                   <div class="Item_Details">
                       <a href="" class="Item_Details-shop-name margin-bottom-10">Shop name</a>
                       <p class="Item_Details-item-price margin-bottom-10"><span>$</span>12.45</p>
                   </div>
                   
                   <div class="Item_ItemLink">
                       <a href="" class="ItemLink-closer-look">Take a closer look</a>
                   </div>
               </div>`,
    props: ['item'],
};