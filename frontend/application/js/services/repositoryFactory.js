import {routes} from "../apiRoutes";

class TodaysProductsRepository {
    getTodaysProducts(data, success) {
        console.log(data);
        const realPath = `${routes.app_get_todays_products}?data=${data}`;

        fetch(realPath)
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
        }
    }
}

export const RepositoryFactory = new Factory();