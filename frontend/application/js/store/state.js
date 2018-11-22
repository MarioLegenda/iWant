export const defaultFilters = {
    bestMatch: true,
    lowestPrice: false,
    highestPrice: false,
    highQuality: false,
    shippingCountries: [],
    marketplaces: [],
    taxonomies: [],
    globalIds: [],
};

export const state = {
    ebaySearchListingLoading: false,
    ebaySearchListing: {
        siteInformation: null,
        items: null
    },
    loadMoreSearchListing: {
        siteInformation: null,
        items: null
    },
    searchTerm: null,
    searchInitialiseEvent: {
        searchUrl: null,
        model: null,
        initialised: false,
        finished: false,
    },
    filtersEvent: defaultFilters,
    translationsMap: {},
    rangeEvent: {
        lowestPrice: false,
    },
};