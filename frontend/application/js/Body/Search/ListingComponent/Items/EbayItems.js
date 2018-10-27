import {Item} from "../../../Listing/components/Item";

const LoadMore = {
    data: function() {
        return {
            selected: false,
            pages: {},
        }
    },
    template: `<div class="LoadMoreWrapper">
               </div>`,
    methods: {
    }
};

export const EbayItems = {
    data: function() {
        return {
        }
    },
    template: `
            <div class="EbayItems SearchItems" id="EbayItemsId">
                
            </div>
            `,
    props: ['classList'],
    computed: {
        searchInitialiseEvent: function() {
            return this.$store.state.searchInitialiseEvent;
        },
    },
    components: {
        'item': Item,
        'load-more': LoadMore,
    }
};