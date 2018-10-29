import {Item} from "../../../Listing/components/Item";

const LoadMore = {
    data: function() {
        return {
            selected: false,
            pages: {},
        }
    },
    template: `<div class="LoadMoreWrapper">
               </div>`,
    methods: {
    }
};

export const EbayItems = {
    data: function() {
        return {
        }
    },
    template: `
            <div class="EbayItems" id="EbayItemsId">
                <div v-for="(item, index) in [1, 2, 3, 4, 5, 6, 7, 8, 9]" :key="index" class="EbayItem SearchItem">
                    <div class="Row ImageWrapper">
                        <img class="Image" src="/images/start_page_background.jpg" />
                    </div>
                    
                    <div class="Row TitleWrapper">
                        <p>Some title some title</p>
                    </div>
                    
                    <div class="Row PriceWrapper">
                        <p class="Price">24.58</p>
                    </div>
                    
                    <div class="Row QuickLookWrapper">
                        <button>Quick look<i class="fas fa-caret-right"></i></button>
                    </div>
                    
                    <div class="Row FullDetailsWrapper">
                        <button>Full details<i class="fas fa-caret-right"></i></button>
                    </div>
                    
                    <div class="Row MarketplaceWrapper">
                        <a href="#">View on eBay</a>
                    </div>
                </div>
            </div>
            `,
    props: ['classList'],
    computed: {

    },
    components: {
        'item': Item,
        'load-more': LoadMore,
    }
};