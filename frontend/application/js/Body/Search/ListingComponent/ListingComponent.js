import {EbayItems} from "./Items/EbayItems";

export const ListingComponent = {
    template: `
        <div>
            <ebay-items
                classList="Item SearchItemItem">
            </ebay-items>
        </div>
    `,
    components: {
        'ebay-items': EbayItems,
    }
};