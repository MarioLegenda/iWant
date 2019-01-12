import {SAVED_STATE_MODE, RESTORING_STATE_MODE, STATE_RESTORE_MODE, SEARCH_TERM} from "./constants";

export const defaultFilters = {
    sortingMethod: 'bestMatch',
    newlyListed: false,
    lowestPrice: false,
    highestPrice: false,
    highQuality: false,
    shippingCountries: [],
    taxonomies: [],
    globalIds: [],
    hideDuplicateItems: false,
    doubleLocaleSearch: false,
    fixedPrice: false,
    brandSearch: false,
    searchQueryFilter: false,
};

export const defaultSearchState = {
    [SAVED_STATE_MODE]: false,
    [RESTORING_STATE_MODE]: false,
    [STATE_RESTORE_MODE]: false,
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
    // loading state
    ebaySearchListingLoading: false,
    preparingProductsLoading: false,
    translatingProductsLoading: false,
    ebay404EmptyResult: false,

    // errors

    httpRequestFailed: false,

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
    savedSearchStateMode: defaultSearchState,
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