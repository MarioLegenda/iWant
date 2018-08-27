import {LOWEST_PRICE} from "./constants";

export class SearchData {
    constructor(keywords, filters) {
        this.keywords = keywords;
        this.filters = this.configureFilters(filters);
    }

    configureFilters(filters) {
        if (filters.length === 0) {
            return [LOWEST_PRICE];
        }

        let filterTypes = [];

        for (const filter of filters) {
            filterTypes.push(filter.data);
        }

        return filterTypes;
    }
}