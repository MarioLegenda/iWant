import {SiteLanguageChoice} from "./SiteLanguageChoice";

export const Header = {
    template: `<div id="main_header">
                   <header>
                       <a @click="toRoot" class="logo">
                           <span class="logo-part i-part">i</span>
                           <span class="logo-part would-part">Would</span>
                           <span class="logo-part like-part">Like</span>
                           <span class="logo-part version-part">Beta version</span>
                       </a>
                   </header>
                   
                   <site-language-choice></site-language-choice>

               </div>`,
    methods: {
        toRoot() {
            const locale = window.location.pathname.split('/')[1];

            this.$router.push({
                name:'Home',
                params: {
                    locale: locale
                }
            });
        }
    },
    components: {
        'site-language-choice': SiteLanguageChoice,
    }
};
