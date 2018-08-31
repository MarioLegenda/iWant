import {Item} from "./Item";

export const UniformedSearchList = {
    template: `
        <div class="search-listing-wrapper wrap">
            <div v-if="ebay.length !== 0" class="search-listing">
                <h1>Ebay</h1>
                <item v-for="(item, index) in ebay" :key="index" v-bind:item="item"></item>
            </div>
        </div>
    `,
    computed: {
        ebay() {
            return this.$store.state.uniformedSearchListing.ebay;
        },
        uniformedSearchListing() {
            this.ebay = this.$store.state.uniformedSearchListing.ebay;
        }
    },
    components: {
        'item': Item
    }
};