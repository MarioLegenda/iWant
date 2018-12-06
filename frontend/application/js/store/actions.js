import Vue from "vue";
import {translationsMap} from "../translationMap";
import {RepositoryFactory} from "../services/repositoryFactory";
import {defaultFilters, defaultModel} from "./state";

export const actions = {
    localeChangedAction(context, locale) {
        Vue.prototype.$localeInfo.locale = locale.value;

        context.commit('localeChanged', locale);

        context.commit('translationsMap', translationsMap[locale.value]);
    },

    destroyEntireState(context) {
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

    totalListingUpdate(context, model) {
        const searchRepo = RepositoryFactory.create('search');

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

        searchRepo.optionsForProductListing(model, (r) => {
            const data = r.resource.data;

            switch (data.method) {
                case 'POST':
                    searchRepo.postPrepareSearchProducts(JSON.stringify({
                        searchData: model,
                    })).then(() => {
                        searchRepo.getProducts(model).then((r) => {
                            context.commit('ebaySearchListing', r.collection.data);
                            context.commit('totalListing', r.collection.data.items);
                            context.commit('ebaySearchListingLoading', false);
                            context.commit('modelWasUpdated', model);
                            context.commit('listingInitialiseEvent', {
                                initialised: true,
                            });
                        });
                    });

                    break;
                case 'GET':
                    searchRepo.getProducts(model, (r) => {
                        context.commit('ebaySearchListing', r.collection.data);
                        context.commit('totalListing', r.collection.data.items);
                        context.commit('ebaySearchListingLoading', false);
                        context.commit('modelWasUpdated', model);
                        context.commit('listingInitialiseEvent', {
                            initialised: true,
                        });
                    });

                    break;
                default:
                    throw new Error(`Invalid option for search listing given. Method can only be POST or GET, ${data.method} given`)
            }
        });
    },

    loadProductListing(context, model) {
        const searchRepo = RepositoryFactory.create('search');

        context.commit('ebaySearchListingLoading', true);

        searchRepo.optionsForProductListing(model, (r) => {
            const data = r.resource.data;

            switch (data.method) {
                case 'POST':
                    searchRepo.postPrepareSearchProducts(JSON.stringify({
                        searchData: model,
                    })).then(() => {
                        searchRepo.getProducts(model).then((r) => {
                            context.commit('ebaySearchListing', r.collection.data);
                            context.commit('totalListing', r.collection.data.items);
                            context.commit('modelWasUpdated', model);
                            context.commit('listingInitialiseEvent', {
                                initialised: true,
                            });
                            context.commit('ebaySearchListingLoading', false);
                        });
                    });

                    break;
                case 'GET':
                    searchRepo.getProducts(model, (r) => {
                        context.commit('ebaySearchListing', r.collection.data);
                        context.commit('totalListing', r.collection.data.items);
                        context.commit('modelWasUpdated', model);
                        context.commit('listingInitialiseEvent', {
                            initialised: true,
                        });
                        context.commit('ebaySearchListingLoading', false);
                    });

                    break;
                default:
                    throw new Error(`Invalid option for search listing given. Method can only be POST or GET, ${data.method} given`)
            }
        });
    }
};