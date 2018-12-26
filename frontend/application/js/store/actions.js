import Vue from "vue";
import {translationsMap} from "../translationMap";
import {RepositoryFactory} from "../services/repositoryFactory";
import {defaultFilters} from "./state";
import {RESTORING_STATE_MODE, SAVED_STATE_MODE, STATE_RESTORE_MODE} from "./constants";

export const actions = {
    localeChangedAction(context, locale) {
        Vue.prototype.$localeInfo.locale = locale.value;

        context.commit('localeChanged', locale);

        context.commit('translationsMap', translationsMap[locale.value]);
    },

    changeSortingMethod(context, method) {
        const sortingMethods = ['bestMatch', 'newlyListed'];

        if (!sortingMethods.includes(method)) {
            throw new Error(`Invalid sorting method given. Expected ${sortingMethods.join(',')}, got ${method}`);
        }

        context.commit('filtersEvent', {
            sortingMethod: method
        });
    },

    saveSearchState(context) {
        context.commit('savedSearchStateMode', {
            savedStateMode: true,
        });

        context.commit('searchInitialiseEvent', {
            searchUrl: null,
            initialised: false,
        });

        context.commit('listingInitialiseEvent', {
            initialised: false,
        });

        context.commit('ebaySearchListingLoading', false);
    },

    restoreSearchState(context) {
        context.commit('searchInitialiseEvent', {
            searchUrl: null,
            initialised: true,
        });

        context.commit('listingInitialiseEvent', {
            initialised: true,
        });

        setTimeout(() => {
            context.commit('savedSearchStateMode', {
                [SAVED_STATE_MODE]: false,
                [RESTORING_STATE_MODE]: true,
            });

            setTimeout(() => {
                context.commit('savedSearchStateMode', {
                    [RESTORING_STATE_MODE]: false,
                    [STATE_RESTORE_MODE]: true,
                });

                setTimeout(() => {
                    context.commit('savedSearchStateMode', null);
                }, 500);
            }, 500);
        }, 500);
    },

    destroyEntireState(context) {
        context.commit('savedSearchStateMode', null);

        context.commit('searchInitialiseEvent', {
            searchUrl: null,
            initialised: false,
        });

        context.commit('listingInitialiseEvent', {
            initialised: false,
        });

        context.commit('ebaySearchListing', {
            siteInformation: null,
            items: null,
        });

        context.commit('totalListing', null);

        context.commit('ebaySearchListing', {
            siteInformation: null,
            items: null
        });

        context.commit('ebaySearchListingLoading', false);

        context.commit('filtersEvent', defaultFilters);
    },

    totalListingUpdate(context, config) {
        const searchRepo = config.searchRepo;
        const model = config.model;

        const errorFunc = function(r) {
            if (r.statusCode === 503) {
                const error = new Error();

                error.response = response;

                throw error;
            }
        };

        context.commit('listingInitialiseEvent', {
            initialised: false,
        });

        context.commit('ebaySearchListing', {
            siteInformation: null,
            items: null,
        });

        context.commit('totalListing', null);
        context.commit('ebaySearchListingLoading', true);
        context.commit('modelWasUpdated', model);
        context.commit('preparingProductsLoading', true);

        searchRepo.optionsForProductListing(model, (r) => {
            const data = r.resource.data;

            errorFunc(r);

            switch (data.method) {
                case 'POST':
                    searchRepo.postPrepareSearchProducts(JSON.stringify({
                        searchData: model,
                    }), (r) => {
                        errorFunc(r);

                        context.commit('preparingProductsLoading', false);
                        context.commit('translatingProductsLoading', true);

                        searchRepo.getProducts(model).then((r) => {
                            errorFunc(r);

                            context.commit('ebaySearchListing', r.collection.data);
                            context.commit('totalListing', r.collection.data.items);
                            context.commit('ebaySearchListingLoading', false);
                            context.commit('modelWasUpdated', model);
                            context.commit('preparedSearchMetadata', {
                                pagination: r.collection.pagination,
                                totalItems: r.collection.data.totalItems,
                            });
                            context.commit('listingInitialiseEvent', {
                                initialised: true,
                            });
                            context.commit('translatingProductsLoading', false);
                        });
                    });

                    break;
                case 'GET':
                    context.commit('translatingProductsLoading', true);

                    searchRepo.getProducts(model, (r) => {
                        errorFunc(r);

                        context.commit('ebaySearchListing', r.collection.data);
                        context.commit('totalListing', r.collection.data.items);
                        context.commit('ebaySearchListingLoading', false);
                        context.commit('modelWasUpdated', model);
                        context.commit('listingInitialiseEvent', {
                            initialised: true,
                        });
                        context.commit('translatingProductsLoading', false);
                    });

                    break;
                default:
                    throw new Error(`Invalid option for search listing given. Method can only be POST or GET, ${data.method} given`)
            }
        });
    },

    // CHECK IF THIS ACTION CAN BE DELETED IF IT IS NOT USED
    loadProductListing(context, model) {
        const searchRepo = RepositoryFactory.create('search');

        context.commit('ebaySearchListingLoading', true);

        searchRepo.optionsForProductListing(model, (r) => {
            const data = r.resource.data;

            switch (data.method) {
                case 'POST':
                    context.commit('preparingProductsLoading', true);

                    searchRepo.postPrepareSearchProducts(JSON.stringify({
                        searchData: model,
                    })).then(() => {
                        context.commit('preparingProductsLoading', false);
                        context.commit('translatingProductsLoading', true);

                        searchRepo.getProducts(model).then((r) => {
                            context.commit('ebaySearchListing', r.collection.data);
                            context.commit('totalListing', r.collection.data.items);
                            context.commit('modelWasUpdated', model);
                            context.commit('listingInitialiseEvent', {
                                initialised: true,
                            });
                            context.commit('ebaySearchListingLoading', false);
                            context.commit('translatingProductsLoading', false);
                        });
                    });

                    break;
                case 'GET':
                    context.commit('translatingProductsLoading', true);

                    searchRepo.getProducts(model, (r) => {
                        context.commit('ebaySearchListing', r.collection.data);
                        context.commit('totalListing', r.collection.data.items);
                        context.commit('modelWasUpdated', model);
                        context.commit('listingInitialiseEvent', {
                            initialised: true,
                        });
                        context.commit('ebaySearchListingLoading', false);
                        context.commit('translatingProductsLoading', true);
                    });

                    break;
                default:
                    throw new Error(`Invalid option for search listing given. Method can only be POST or GET, ${data.method} given`)
            }
        });
    }
};