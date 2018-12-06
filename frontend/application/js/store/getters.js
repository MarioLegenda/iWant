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

    getPreparedSearchMetadata(state, getters) {
        return state.preparedSearchMetadata;
    },

    areSingleAddFiltersSelected(state, getters) {
        const filtersEvent = state.filtersEvent;
        const excludes = ['bestMatch'];

        for (const evn in filtersEvent) {
            if (filtersEvent.hasOwnProperty(evn)) {
                if (excludes.includes(evn)) {
                    continue;
                }

                if (Array.isArray(filtersEvent[evn])) {
                    if (filtersEvent[evn].length > 0) {
                        return true;
                    }
                }

                if (isBoolean(filtersEvent[evn]) && filtersEvent[evn] === true) {
                    return true;
                }
            }
        }

        return false;
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