export const mutations = {
    searchTerm(state, value) {
        this.state.searchTerm = value;
    },

    ebaySearchListing(state, value) {
        this.state.ebaySearchListing = value;
    },

    ebaySearchListingLoading(state, value) {
        this.state.ebaySearchListingLoading = value;
    },

    searchInitialiseEvent(state, value) {
        this.state.searchInitialiseEvent = Object.assign({}, this.state.searchInitialiseEvent, value);
    },

    filtersEvent(state, value) {
        this.state.filtersEvent = Object.assign({}, this.state.filtersEvent, value);
    },

    translationsMap(state, value) {
        this.state.translationsMap = value;
    },

    rangeEvent(state, value) {
        this.state.rangeEvent = Object.assign({}, value);
    },

    loadMoreSearchListing(state, value) {
        this.state.loadMoreSearchListing = value;
    }
};