import {routes} from "../apiRoutes";

function parseJson(response) {
    return response.json();
}

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
    async asyncPostPrepareEbaySearch(data, success, error) {
        const response = await fetch(routes.app_post_prepare_ebay_search, {
            method: 'POST',
            body: JSON.stringify(data),
            headers: requiredHeaders,
        });

        const content = await response.json();

        const normalized = {
            content: content,
            request: data,
        };

        return success(normalized);
    }

    postPrepareEbaySearch(data, success, error) {
        return fetch(routes.app_post_prepare_ebay_search, {
            method: 'POST',
            body: JSON.stringify(data),
            headers: requiredHeaders,
        })
            .then((response) => {
                return response.json()
            })
            .then((response) => {
                return {
                    content: response,
                    request: data,
                };
            })
            .then(success)
            .catch(error);
    }

    getPreparedEbaySearch(data, success, error) {
        const route = routes.createRouteFromName('app_get_prepared_ebay_search', {
            searchData: JSON.stringify({
                searchData: data
            })
        });

        fetch(route, {
            method: 'GET',
            headers: requiredHeaders,
        })
            .then(parseJson)
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

        console.log(route);
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