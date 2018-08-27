import {Item} from "./Item";

export const UniformedSearchList = {
    template: `
        <div class="search-listing-wrapper wrap">
            <div class="search-listing">
                <item v-for="(index, item) in uniformedSearchListing" :key="index" item="item"></item>
            </div>
        </div>
    `,
    computed: {
        uniformedSearchListing() {
            return this.$store.state.uniformedSearchListing;
        }
    },
    components: {
        'item': Item
    }
};