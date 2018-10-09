import {ItemList} from "../Listing/components/ItemList";

export const TodaysPicks = {
    data: function() {
        return {
            currentDay: `${new Date().getDayName()}'s`
        }
    },
    template: `<div id="todays_picks">
                   <h1 class="todays-picks-header">
                       . . . our {{ currentDay }} products for you . . .
                   </h1>
                   
                   <item-list
                       v-bind:items="todaysProductsListing.ebay"
                       classList="Item TodayProductItem">
                   </item-list>
                   
                   <item-list
                       v-bind:items="todaysProductsListing.etsy"
                       classList="Item TodayProductItem">
                   </item-list>
               </div>`,
    computed: {
        todaysProductsListing: function() {
            return this.$store.state.todaysProductsListing;
        }
    },
    components: {
        'item-list': ItemList,
    }
};