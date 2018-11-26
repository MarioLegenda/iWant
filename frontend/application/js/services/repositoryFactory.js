import {routes} from "../apiRoutes";

const requiredHeaders = {
    'X-Requested-With': 'XMLHttpRequest',
};

class AppRepository {
    async asyncGetEbayGlobalIdsInformation(success) {
        const route = routes.app_get_ebay_global_id_information;

        const response = await fetch(route);

        success(await response.json());
    }

    getMarketplaces(data, success) {
        const route = routes.app_get_marketplaces;

        fetch(route)
            .then(function(response) {
                return response.json();
            })
            .then(success);
    }

    getCountries(data, success) {
        const route = routes.app_get_countries;

        fetch(route)
            .then(function(response) {
                return response.json();
            })
            .then(success);
    }

    getNativeTaxonomies(data, success) {
        const route = routes.app_get_taxonomies;

        fetch(route)
            .then(function(response) {
                return response.json();
            })
            .then(success);
    }
}

class SearchRepository {
    getProducts(data, success, error) {
        const route = routes.createRouteFromName('app_get_products_by_global_id', {
            searchData: JSON.stringify(data)
        });

        return fetch(route, {
            method: 'GET',
            headers: requiredHeaders,
        })
            .then((response) => {
                return response.json()
            })
            .then(success)
            .catch(error);
    }

    postPrepareSearchProducts(data, success, error) {
        const route = routes.app_post_products_by_global_id;

        return fetch(route, {
            method: 'POST',
            headers: requiredHeaders,
            body: data,
        })
            .then((response) => {
                return response.json()
            })
            .then(success)
            .catch(error);
    }

    optionsForProductListing(data, success, error) {
        const route = routes.createRouteFromName('app_options_products_listing', {
            searchData: JSON.stringify(data)
        });

        return fetch(route, {
            method: 'GET',
            headers: requiredHeaders,
        })
            .then((response) => {
                return response.json()
            })
            .then(success)
            .catch(error);
    }
}

class SingleItemRepository {
    checkSingleItem(data, success, error) {
        const route = routes.createRouteFromName('app_options_check_single_item', {
            locale: data.locale,
            itemId: data.itemId,
        });

        return fetch(route, {
            method: 'GET',
            headers: requiredHeaders,
        })
            .then((response) => {
                return response.json()
            })
            .then(success)
            .catch(error);
    }

    putSingleItem(data, success, error) {
        return fetch(data.route, {
            method: 'PUT',
            body: JSON.stringify({itemId: data.itemId, locale: data.locale}),
            headers: requiredHeaders,
        })
            .then((response) => {
                return response.json()
            })
            .then(success)
            .catch(error);
    }

    getQuickLookSingleItem(data, success, error) {
        return fetch(data.route, {
            method: 'GET',
            headers: requiredHeaders,
        })
            .then((response) => {
                return response.json();
            })
            .then(success)
            .catch(error);
    }

    getSingleItem(data, success, error) {
        const route = routes.createRouteFromName('app_get_single_item', data);

        return fetch(route, {
            method: 'GET',
            headers: requiredHeaders,
        })
            .then((response) => {
                return response.json();
            })
            .then(success)
            .catch(error);
    }
}

class Factory {
    constructor() {
        this.repositores = {};
    }

    create(repoName) {
        switch (repoName) {
            case 'single-item':
                if (!this.repositores.hasOwnProperty(repoName)) {
                    this.repositores[repoName] = new SingleItemRepository();
                }

                return this.repositores[repoName];
            case 'search':
                if (!this.repositores.hasOwnProperty(repoName)) {
                    this.repositores[repoName] = new SearchRepository();
                }

                return this.repositores[repoName];
            case 'app':
                if (!this.repositores.hasOwnProperty(repoName)) {
                    this.repositores[repoName] = new AppRepository();
                }

                return this.repositores[repoName];
        }
    }
}

export const RepositoryFactory = new Factory();