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

export const defaultModel = {
    keyword: null,
    filters: {},
    pagination: {
        limit: 8,
        page: 1,
    },
    locale: 'en',
    internalPagination: {
        limit: 80,
        page: 1
    },
    globalId: null,
};

export const state = {
    ebaySearchListingLoading: false,
    ebaySearchListing: {
        siteInformation: null,
        items: null
    },
    totalListing: [],
    searchTerm: null,
    searchInitialiseEvent: {
        searchUrl: null,
        initialised: false,
    },
    listingInitialiseEvent: {
        initialised: false,
    },
    filtersEvent: defaultFilters,
    translationsMap: {},
    modelWasCreated: {
        keyword: null,
        filters: {},
        pagination: {
            limit: 8,
            page: 1,
        },
        locale: 'en',
        internalPagination: {
            limit: 80,
            page: 1
        },
        globalId: null,
    },
    modelWasUpdated: {
        keyword: null,
        filters: {},
        pagination: {
            limit: 8,
            page: 1,
        },
        locale: 'en',
        internalPagination: {
            limit: 80,
            page: 1
        },
        globalId: null,
    },
};