import {TodaysPicks} from "./TodaysPicks";

export const Homepage = {
    template: `<div>
                    <div id="StartPage">
                        <div class="StartPage-background-image">
                            <img src="/images/start_page_background.jpg"/>
                        </div>
                   
                         <div class="StartPage_MissionStatement">
                             <h1 class="MissionStatement-header">Our mission</h1>
                             <div class="MissionStatement_Content">
                                 <span class="Content-full-element-span">To provide you with a unique shopping experience with products from </span>
                                 <span class="Content-full-element-span" id="typed_js_sentence"></span> online marketplace(s). <a href="" class="MissionStatement_Link">Read more</a> about it.
                             </div>
                         </div>
                     </div>
                     
                     <todays-picks></todays-picks>
               </div>`,
    components: {
        'todays-picks': TodaysPicks,
    }
};