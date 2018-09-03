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
                   
                   <item-list></item-list>
                   
                   <h1 class="todays-picks-header">
                       . . . this weeks concerts . . .
                   </h1>
               </div>`,
    components: {
        'item-list': ItemList,
    }
};