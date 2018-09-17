export const routes = {
    app_get_todays_products: '/api/v1/get-todays-products',
    app_get_item: '/api/v1/item/:marketplace/:itemId',

    createRoute: function(routeName, params) {
        if (this.hasOwnProperty(routeName)) {
            let resolvedItem = this.app_get_item;

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