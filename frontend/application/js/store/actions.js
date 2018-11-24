import Vue from "vue";
import {translationsMap} from "../translationMap";
import {RepositoryFactory} from "../services/repositoryFactory";

export const actions = {
    localeChanged(context, locale) {
        Vue.prototype.$localeInfo.locale = locale.value;

        context.commit('translationsMap', translationsMap[locale.value]);
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

        context.commit('totalListing', []);
        context.commit('ebaySearchListingLoading', true);
        context.commit('modelWasUpdated', model);

        searchRepo.getProductsByRange(model, (r) => {
            context.commit('ebaySearchListing', r.collection.data);
            context.commit('totalListing', r.collection.data.items);
            context.commit('ebaySearchListingLoading', false);
            context.commit('modelWasUpdated', model);
            context.commit('listingInitialiseEvent', {
                initialised: true,
            });
        });
    },

    loadProductListing(context, model) {
        const searchRepo = RepositoryFactory.create('search');

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
    }
};