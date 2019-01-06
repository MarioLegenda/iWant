export const routes = {
    app_get_item: '/api/v1/item/:marketplace/:itemId',
    app_get_countries: '/api/v1/get-countries',
    app_get_marketplaces: '/api/v1/get-marketplaces',
    app_get_taxonomies: '/api/v1/get-taxonomies',
    app_get_ebay_global_id_information: '/api/v1/get-ebay-global-id-information',
    app_post_activity_message: '/api/v1/activity-message',
    app_get_single_item: '/api/v1/ebay/get-single-item/:locale/:itemId',
    app_post_products_by_global_id: '/api/v1/ebay/prepare-products',
    app_get_products_by_global_id: '/api/v1/ebay/get-products-by-global-id/:searchData',
    app_options_products_listing: '/api/v1/ebay/get-product-listing-options/:searchData',
    app_options_check_single_item: '/api/v1/ebay/check-single-item/:locale/:itemId',
    app_get_shipping_costs: '/api/v1/ebay/get-shipping-costs/:locale/:itemId/:destinationCountryCode',
    app_post_contact_message: '/api/v1/contact-message',

    createRouteFromName: function(routeName, params) {
        if (this.hasOwnProperty(routeName)) {
            let resolvedItem = this[routeName];

            for (let param in params) {
                if (params.hasOwnProperty(param)) {
                    let realParam = params[param];

                    let testReg = new RegExp(`:${param}`);

                    if (testReg.test(resolvedItem)) {
                        let replaceRegex = new RegExp(`:${param}`);

                        resolvedItem = resolvedItem.replace(replaceRegex, realParam);
                    }
                }
            }

            return resolvedItem;
        }

        return null;
    },

    createRoute: function(route, params) {
        for (let param in params) {
            if (params.hasOwnProperty(param)) {
                let realParam = params[param];

                let testReg = new RegExp(`:${param}`);

                if (testReg.test(route)) {
                    let replaceRegex = new RegExp(`:${param}`);

                    route = route.replace(replaceRegex, realParam);
                }
            }
        }

        return route;
    }
};