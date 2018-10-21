import {SearchBox} from "./SearchBox";

export const Header = {
    template: `<div id="main_header">
                   <header>
                       <router-link to="/" class="logo" exact>
                           <span class="logo-part i-part">i</span>
                           <span class="logo-part would-part">Would</span>
                           <span class="logo-part like-part">Like</span>
                       </router-link>
                   </header>
                   
                   <router-view name="search-box"></router-view>
               </div>`,
    components: {
        'search-box': SearchBox,
    }
};
