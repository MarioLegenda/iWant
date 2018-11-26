export const mutations = {
    searchTerm(state, value) {
        console.log('searchTerm event');
        this.state.searchTerm = value;
    },

    ebaySearchListing(state, value) {
        console.log('ebaySearchListing event');
        this.state.ebaySearchListing = value;
    },

    ebaySearchListingLoading(state, value) {
        console.log('ebaySearchListingLoading event');

        this.state.ebaySearchListingLoading = value;
    },

    searchInitialiseEvent(state, value) {
        console.log('searchInitialiseEvent event');

        this.state.searchInitialiseEvent = Object.assign({}, this.state.searchInitialiseEvent, value);
    },

    filtersEvent(state, value) {
        console.log('filtersEvent event');

        this.state.filtersEvent = Object.assign({}, this.state.filtersEvent, value);
    },

    localeChanged(state, value) {
        console.log('localeChanged event');

        this.state.localeChanged = value;
    },

    translationsMap(state, value) {
        console.log('translationsMap event');

        this.state.translationsMap = value;
    },

    loadMoreSearchListing(state, value) {
        console.log('loadMoreSearchListing event');

        this.state.loadMoreSearchListing = value;
    },

    listingInitialiseEvent(state, value) {
        console.log('listingInitialiseEvent event');

        this.state.listingInitialiseEvent = Object.assign({}, this.state.listingInitialiseEvent, value);
    },

    modelWasCreated(state, value) {
        console.log('modelWasCreated event');

        this.state.modelWasCreated = value;
    },

    modelWasUpdated(state, value) {
        console.log('modelWasUpdated event');

        this.state.modelWasUpdated = Object.assign({}, this.state.modelWasUpdated, value);
    },

    totalListing(state, value) {
        if (value === null) {
            state.totalListing = [];

            return;
        }

        value.forEach((item) => state.totalListing.push(item));
    }
};