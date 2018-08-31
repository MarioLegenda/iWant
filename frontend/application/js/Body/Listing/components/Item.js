export const Item = {
    template: `<div class="search-item">
                    <div class="shop-logo wrap">
                        <img src="/images/ebay-logo.jpg" />
                    </div>
                    
                    <span class="bordered-row-separator"></span>
                                       
                    <div class="item-image">
                        <img src="https://images.bonanzastatic.com/afu/images/1747/4109/99/Pussnboots_thumb155_crop.jpg" />
                    </div>
                    
                    <div class="title-wrapper wrap">
                        <h1>{{ item.title }}</h1>
                    </div>
                    
                    <div class="account-wrapper wrap">
                        <h1><a href="">Seller name</a></h1>
                    </div>
                    
                    <div class="price-wrapper wrap">
                        <h1>
                            <span class="price-info">{{ item.price }} $</span>
                        </h1>
                    </div>
                    
                    <div class="view-item-button-wrapper wrap">
                        <a v-bind:href="item.viewItemUrl">View item</a>
                    </div>
               </div>`,
    props: ['item']
};