import {EbayItems} from "./Items/EbayItems";
import {RepositoryFactory} from "../../../services/repositoryFactory";

export const ListingComponent = {
    template: `
        <div class="SearchItems">
            <ebay-items
                classList="Item SearchItemItem">
            </ebay-items>
        </div>
    `,
    components: {
        'ebay-items': EbayItems,
    }
};