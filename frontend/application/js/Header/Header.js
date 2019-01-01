import {SiteLanguageChoice} from "./SiteLanguageChoice";

export const Header = {
    template: `<div id="main_header">
                   <header>
                       <router-link to="/" class="logo" exact>
                           <span class="logo-part i-part">i</span>
                           <span class="logo-part would-part">Would</span>
                           <span class="logo-part like-part">Like</span>
                           <span class="logo-part version-part">Beta version</span>
                       </router-link>
                   </header>
                   
                   <site-language-choice></site-language-choice>

               </div>`,
    components: {
        'site-language-choice': SiteLanguageChoice,
    }
};
