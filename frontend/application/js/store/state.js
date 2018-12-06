export const defaultFilters = {
    bestMatch: true,
    lowestPrice: false,
    highestPrice: false,
    highQuality: false,
    shippingCountries: [],
    taxonomies: [],
    globalIds: [],
    hideDuplicateItems: false,
    doubleLocaleSearch: false,
    fixedPrice: false,
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
    preparedSearchMetadata: {
        pagination: {limit: null, page: null},
        totalItems: null
    },
    totalListing: [],
    searchInitialiseEvent: {
        searchUrl: null,
        initialised: false,
    },
    listingInitialiseEvent: {
        initialised: false,
    },
    localeChanged: {
        value: '',
        origin: null,
    },
    filtersEvent: defaultFilters,
    translationsMap: {},
    modelWasCreated: defaultModel,
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