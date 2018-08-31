import {ENTRIES_PER_PAGE, LOWEST_PRICE} from "./constants";

export class SearchData {
    constructor(keywords, filters) {
        this.keywords = keywords;
        this.filters = this.configureFilters(filters);
    }

    configureFilters(filters) {
        let filterTypes = [];

        if (filters.length === 0) {
            filterTypes.push({
                filterType: LOWEST_PRICE,
                data: []
            });

            filterTypes.push({
                filterType: ENTRIES_PER_PAGE,
                data: [12]
            });

            return filterTypes;
        }

        for (const filter of filters) {
            filterTypes.push(filter.data);
        }

        return filterTypes;
    }
}