import {EbayLoading} from "./EbayLoading";

export const LoadingComponent = {
    template: `
            <div class="LoadingComponent">
                <ebay-loading-component></ebay-loading-component>
            </div>
    `,
    components: {
        'ebay-loading-component': EbayLoading,
    }
};