export const routes = {
    app_get_todays_products: '/api/v1/get-todays-products',
    app_get_item: '/api/v1/item/:marketplace/:itemId',
    app_get_countries: '/api/v1/get-countries',
    app_get_marketplaces: '/api/v1/get-marketplaces',
    app_get_taxonomies: '/api/v1/get-taxonomies',
    app_get_search: '/api/v1/search/ebay/:searchData',
    app_get_ebay_global_id_information: '/api/v1/get-ebay-global-id-information',
    app_post_activity_message: '/api/v1/activity-message',

    createRoute: function(routeName, params) {
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
    }
};