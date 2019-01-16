import {routes} from "../apiRoutes";

const requiredHeaders = {
    'X-Requested-With': 'XMLHttpRequest',
    'HTTP-API-I-WOULD-LIKE': 'api',
    'X-Frame-Options': 'deny',
};

function checkStatus(response) {
    if (response.status === 503) {
        const error = new Error(response.statusText)

        error.response = response;
        error.type = 'application';

        throw error;
    }

    if (response.status === 500) {
        const error = new Error(response.statusText);

        error.response = response;
        error.type = 'server';

        throw error;
    }

    return response;
}

function errorHandler(error) {
    if (error.type === 'application') {
        return error.response.json();
    }

    if (error.type === 'server') {
        console.log(error);
        return {
            statusCode: error.response.status,
            response: error.response.json(),
        }
    }
}

class Repository {}

class AppRepository extends Repository {
    async asyncGetEbayGlobalIdsInformation(success) {
        const route = routes.app_get_ebay_global_id_information;

        const response = await fetch(route,{
            method: 'GET',
            headers: requiredHeaders,
        });

        success(await response.json());
    }

    async asyncGetCountries(success) {
        const route = routes.app_get_countries;

        const response = await fetch(route,{
            method: 'GET',
            headers: requiredHeaders,
        });

        success(await response.json());
    }

    getCountries(data, success, error) {
        const route = routes.app_get_countries;

        fetch(route, {
            method: 'GET',
            headers: requiredHeaders,
        })
            .then(function(response) {
                return response.json();
            })
            .then(success)
            .catch(error);
    }

    postSendContactMessage(data, success, error) {
        const route = routes.app_post_contact_message;

        return fetch(route, {
            method: 'POST',
            headers: requiredHeaders,
            body: data,
        })
            .then(checkStatus)
            .then((response) => {
                return response.json()
            })
            .then(success)
            .catch(error);
    }
}

class SearchRepository extends Repository {
    getProducts(data, success, error) {
        const route = routes.createRouteFromName('app_get_products_by_global_id', {
            searchData: JSON.stringify(data)
        });

        return fetch(route, {
            method: 'GET',
            headers: requiredHeaders,
        })
            .then(checkStatus)
            .then((response) => {
                return response.json()
            })
            .then(success)
            .catch(errorHandler)
            .then(Repository.errorHandler);
    }

    postPrepareSearchProducts(data, success, error) {
        const route = routes.app_post_products_by_global_id;

        return fetch(route, {
            method: 'POST',
            headers: requiredHeaders,
            body: data,
        })
            .then(checkStatus)
            .then((response) => {
                return response.json()
            })
            .then(success)
            .catch(errorHandler)
            .then(Repository.errorHandler);
    }

    optionsForProductListing(data, success, error) {
        const route = routes.createRouteFromName('app_options_products_listing', {
            searchData: JSON.stringify(data)
        });

        return fetch(route, {
            method: 'GET',
            headers: requiredHeaders,
        })
            .then(checkStatus)
            .then((response) => {
                return response.json()
            })
            .then(success)
            .catch(errorHandler)
            .then(Repository.errorHandler);
    }
}

class SingleItemRepository extends Repository {
    checkSingleItem(data, success, error) {
        const route = routes.createRouteFromName('app_options_check_single_item', {
            locale: data.locale,
            itemId: data.itemId,
        });

        return fetch(route, {
            method: 'GET',
            headers: requiredHeaders,
        })
            .then(checkStatus)
            .then((response) => {
                return response.json()
            })
            .then(success)
            .catch(errorHandler)
            .then(Repository.errorHandler);
    }

    putSingleItem(data, success, error) {
        return fetch(data.route, {
            method: 'PUT',
            body: JSON.stringify({itemId: data.itemId, locale: data.locale}),
            headers: requiredHeaders,
        })
            .then(checkStatus)
            .then((response) => {
                return response.json()
            })
            .then(success)
            .catch(errorHandler)
            .then(Repository.errorHandler);
    }

    getQuickLookSingleItem(data, success, error) {
        return fetch(data.route, {
            method: 'GET',
            headers: requiredHeaders,
        })
            .then(checkStatus)
            .then((response) => {
                return response.json();
            })
            .then(success)
            .catch(errorHandler)
            .then(Repository.errorHandler);
    }

    getSingleItem(data, success, error) {
        const route = routes.createRouteFromName('app_get_single_item', data);

        return fetch(route, {
            method: 'GET',
            headers: requiredHeaders,
        })
            .then(checkStatus)
            .then((response) => {
                return response.json();
            })
            .then(success)
            .catch(errorHandler)
            .then(Repository.errorHandler);
    }

    getShippingCosts(data, success, error) {
        const route = routes.createRouteFromName('app_get_shipping_costs', data);

        return fetch(route, {
            method: 'GET',
            headers: requiredHeaders,
        })
            .then(checkStatus)
            .then((response) => {
                return response.json()
            })
            .then(success)
            .catch(errorHandler)
            .then(Repository.errorHandler);
    }
}

export class RepositoryFactory {
    constructor(successHandler, errorHandler) {
        this.repositores = {};

        Repository.successHandler = successHandler;
        Repository.errorHandler = errorHandler;
    }

    get SingleItemRepository() {
        if (!this.repositores.hasOwnProperty('single-item')) {
            this.repositores['single-item'] = new SingleItemRepository();
        }

        return this.repositores['single-item'];
    }

    get SearchRepository() {
        if (!this.repositores.hasOwnProperty('search')) {
            this.repositores['search'] = new SearchRepository();
        }

        return this.repositores['search'];
    }

    get AppRepository() {
        if (!this.repositores.hasOwnProperty('app')) {
            this.repositores['app'] = new AppRepository();
        }

        return this.repositores['app'];
    }
}