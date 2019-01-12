export const Navigation = {
    template: `<div id="navigation">
                   <div class="NavigationWrapper">
                       <div class="NavItem">
                           <router-link to="" v-on:click.native="_pushRoute('Guide', $event)" class="NavActionItem">
                               {{translationsMap.navigation.guideTitle}}
                               <i class="fas fa-book-open"></i>
                           </router-link>
                       </div>
                       
                       <div class="NavItem">
                           <router-link to="" v-on:click.native="_pushRoute('Features', $event)" class="NavActionItem">
                               {{translationsMap.navigation.featuresTitle}}
                               <i class="fas fa-scroll"></i>
                           </router-link>
                       </div>
                       
                       <div class="NavItem">
                           <router-link to="" v-on:click.native="_pushRoute('Progress', $event)" class="NavActionItem">
                               {{translationsMap.navigation.progressTitle}}
                               <i class="fas fa-cogs"></i>
                           </router-link>
                       </div>
                       
                       <div class="NavItem">
                           <router-link to="" v-on:click.native="_pushRoute('ForYou', $event)" class="NavActionItem">
                               {{translationsMap.navigation.forYouTitle}}
                               <i class="fas fa-american-sign-language-interpreting"></i>
                           </router-link>
                       </div>
                   </div>
               </div>`,
    computed: {
        translationsMap: function() {
            return this.$store.state.translationsMap;
        },
    },
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
