export const getters = {
    getSearchListing(state, getters) {
        return state.ebaySearchListing.items;
    },

    getTotalListings(state, getters) {
        return state.totalListing;
    },

    getSiteInformation(state, getters) {
        return state.ebaySearchListing.siteInformation;
    },

    isListingInitialised(state, getters) {
        return state.listingInitialiseEvent.initialised;
    },

    getEbaySearchListingLoading(state, getters) {
        return state.ebaySearchListingLoading;
    },

    getSearchInitialiseEvent(state, getters) {
        return state.searchInitialiseEvent;
    },

    isSearchInitialised(state, getters) {
        return getters.getSearchInitialiseEvent.initialised;
    },

    getTranslationsMap(state, getters) {
        return state.translationsMap;
    },

    getModel(state, getters) {
        return state.modelWasUpdated;
    },

    getFilters(state, getters) {
        return state.filtersEvent;
    }
};