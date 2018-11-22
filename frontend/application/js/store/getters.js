export const getters = {
    getSearchListing(state, getters) {
        return state.ebaySearchListing;
    },

    getEbaySearchListingLoading(state, getters) {
        return state.ebaySearchListingLoading;
    },

    getSearchInitialiseEvent(state, getters) {
        return state.searchInitialiseEvent;
    },

    getRangeEvent(state, getters) {
        return state.rangeEvent;
    },

    getTranslationsMap(state, getters) {
        return state.translationsMap;
    },

    getMoreLoadedSearchListings(state, getters) {
        return state.loadMoreSearchListing;
    }
};