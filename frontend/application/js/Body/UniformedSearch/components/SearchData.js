export const SearchData = {
    computed: {
        keywords: function() {

        },

        filters: function() {

        },
        searchData: function() {
            return {
                keywords: this.keywords,
                filters: this.filters,
            }
        }
    }
};