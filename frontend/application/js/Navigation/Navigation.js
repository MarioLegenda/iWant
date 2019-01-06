export const Navigation = {
    template: `<div id="navigation">
                   <div class="NavigationWrapper">
                       <div class="NavItem">
                           <router-link to="" v-on:click.native="_pushRoute('Guide', $event)" class="NavActionItem">GUIDE /</router-link>
                       </div>
                       
                       <div class="NavItem">
                           <router-link to="" v-on:click.native="_pushRoute('Features', $event)" class="NavActionItem">FEATURES /</router-link>
                       </div>
                       
                       <div class="NavItem">
                           <router-link to="" v-on:click.native="_pushRoute('Progress', $event)" class="NavActionItem">PROGRESS /</router-link>
                       </div>
                       
                       <div class="NavItem">
                           <router-link to="" v-on:click.native="_pushRoute('ForYou', $event)" class="NavActionItem">for You /</router-link>
                       </div>
                   </div>
               </div>`,
    methods: {
        _pushRoute(routeName, $event) {
            {
/*                const target = $event.target;
                const toggleHighlight = function() {
                    const navItems = document.getElementById('navigation').getElementsByTagName('a');

                    for (const a of navItems) {
                        a.classList.remove('HighlightNavigation');
                    }

                    target.classList.add('HighlightNavigation');
                };

                toggleHighlight();*/
            }

            vueRouterLinkCreate.bind(this)(routeName);
        }
    }
};
