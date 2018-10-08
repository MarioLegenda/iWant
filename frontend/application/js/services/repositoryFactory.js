import {routes} from "../apiRoutes";

class TodaysProductsRepository {
    getTodaysProducts(data, success) {
        const route = `${routes.app_get_todays_products}?data=${data}`;

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

class MarketplaceRepository {
    getMarketplaces(data, success) {
        const route = routes.app_get_marketplaces;

        fetch(route)
            .then(function(response) {
                return response.json();
            })
            .then(success);
    }
}

class CountryRepository {
    getCountries(data, success) {
        const route = routes.app_get_countries;

        fetch(route)
            .then(function(response) {
                return response.json();
            })
            .then(success);
    }
}

class TaxonomyRepository {
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
    searchEbay(data, success) {
        const route = routes.createRoute('app_get_search', {
            searchData: JSON.stringify(data),
        });

        fetch(route)
            .then(function(response) {
                return response.json();
            })
            .then(success);
    }
}

class Factory {
    constructor() {
        this.repositores = {};
    }

    create(repoName) {
        switch (repoName) {
            case 'todays-products':
                if (!this.repositores.hasOwnProperty(repoName)) {
                    this.repositores[repoName] = new TodaysProductsRepository();
                }

                return this.repositores[repoName];
            case 'single-item':
                if (!this.repositores.hasOwnProperty(repoName)) {
                    this.repositores[repoName] = new SingleItemRepository();
                }

                return this.repositores[repoName];
            case 'country':
                if (!this.repositores.hasOwnProperty(repoName)) {
                    this.repositores[repoName] = new CountryRepository();
                }

                return this.repositores[repoName];
            case 'marketplace':
                if (!this.repositores.hasOwnProperty(repoName)) {
                    this.repositores[repoName] = new MarketplaceRepository();
                }

                return this.repositores[repoName];
            case 'taxonomy':
                if (!this.repositores.hasOwnProperty(repoName)) {
                    this.repositores[repoName] = new TaxonomyRepository();
                }

                return this.repositores[repoName];
            case 'search':
                if (!this.repositores.hasOwnProperty(repoName)) {
                    this.repositores[repoName] = new SearchRepository();
                }

                return this.repositores[repoName];
        }
    }
}

export const RepositoryFactory = new Factory();