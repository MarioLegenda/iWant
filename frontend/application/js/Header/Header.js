import {SearchBox} from "./SearchBox";

export const Header = {
    template: `<div id="main_header">
                   <header>
                       <router-link to="/" class="logo" exact>
                           <span class="logo-part i-part">i</span>
                           <span class="logo-part am-part">Would</span>
                           <span class="logo-part buying-part">Like</span>
                       </router-link>
                   </header>
                   
                   <router-view name="search-box"></router-view>
               </div>`,
    components: {
        'search-box': SearchBox,
    }
};
