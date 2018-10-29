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

class SingleItemRepository {
    getSingleItem(data, success) {
        const route = routes.createRoute('app_get_item', {
            marketplace: data.marketplace,
            itemId: data.itemId
        });

        fetch(route)
            .then(function(response) {
                return response.json();
            })
            .then(success);
    }
}

class SearchRepository {
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
        const route = routes.createRoute('app_get_prepared_ebay_search', {
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