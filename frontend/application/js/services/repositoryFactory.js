import {routes} from "../apiRoutes";

class UniformedSearchRepository {
    getUniformedSearch(data, success) {
        const realPath = `${routes.app_get_uniformed_search_result}?data=${data}`;

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
            case 'uniformed-search':
                if (!this.repositores.hasOwnProperty(repoName)) {
                    this.repositores[repoName] = new UniformedSearchRepository();
                }

                return this.repositores[repoName];
        }
    }
}

export const RepositoryFactory = new Factory();