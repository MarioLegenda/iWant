export class SearchProvider {
    constructor(searchData) {
        this.keywords = searchData.keywords;
        this.filters = searchData.filters;
    }

    provide($store) {

    }
}