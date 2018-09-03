import {TodaysPicks} from "./TodaysPicks";

export const Homepage = {
    template: `<div>
                    <div id="start_page">
                        <div class="background-image">
                            <img src="/images/start_page_background.jpg"/>
                        </div>
                   
                         <div class="mission-statement">
                             <h1>Our mission</h1>
                             <p>
                                 To provide you with a unique shopping experience with products from the best online marketplace(s). <span>Read more</span> about it.
                             </p>
                         </div>
                     </div>
                     
                     <todays-picks></todays-picks>
               </div>`,
    components: {
        'todays-picks': TodaysPicks,
    }
};