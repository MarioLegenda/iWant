import {RepositoryFactory} from "../../services/repositoryFactory";

export class SearchProvider {
    constructor(searchData) {
        this.keywords = searchData.keywords;
        this.filters = searchData.filters;
    }

    provide($store) {
        const uniformedSearchRepository = RepositoryFactory.create('uniformed-search');
        const data = {
            keywords: this.keywords,
            filters: this.filters,
        };

        uniformedSearchRepository.getUniformedSearch(JSON.stringify(data), function(data) {
            $store.commit('uniformedSearchListing', data);
        });
    }
}