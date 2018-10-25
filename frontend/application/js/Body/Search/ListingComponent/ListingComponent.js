import {EbayItems} from "../Items/EbayItems";
import {EtsyItems} from "../Items/EtsyItems";

export const ListingComponent = {
    template: `
        <div>
            <ebay-items
                classList="Item SearchItemItem">
            </ebay-items>
                    
            <etsy-items
                classList="Item SearchItemItem">
            </etsy-items>
        </div>
    `,
    components: {
        'ebay-items': EbayItems,
        'etsy-items': EtsyItems,
    }
};