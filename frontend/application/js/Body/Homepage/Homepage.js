export const Homepage = {
    template: `<transition name="fade">

                <div class="HomepageWrapper">
                    <div class="Homepage">
                        <div class="Row">
                            <p class="Advert"><span class="OrangeHighlight">one</span> <span class="LightFontWeight">website</span></p>
                        </div>
                        
                        <div class="Row">
                            <p class="Advert"><span class="LightFontWeight">entire</span> <span class="OrangeHighlight">world</span> <span class="LightFontWeight">of eBay</span></p>
                        </div>
                        
                        <div class="Row">
                            <p class="Advert"><span class="OrangeHighlight">translated</span> <span class="LightFontWeight">to your language</span></p>
                        </div>
                        
                        <div class="Row">
                            <p class="Advert"><span class="LightFontWeight">whatever</span> <span class="BlueHighlight">you</span> <span class="GreenHighlight">would</span> <span class="OrangeHighlight">like</span></p>
                        </div>
                        
                        <div class="Row">
                            <div class="HomepageRouter">
                                <span class="RouterText">Checkout the</span> 
                                
                                <router-link to="" class="Link">features</router-link>
                                
                                <span class="RouterText">or</span> 
                                
                                <router-link to="" v-on:click.native="pushGetStartedLink" class="Link">get started</router-link>
                            </div>
                        </div>
                    </div>
               </div>
               
               </transition>`,
    methods: {
        pushGetStartedLink: function() {
            console.log('click');
            this.$router.push({
                name: 'SearchHome',
                params: { locale: this.$localeInfo.locale }
            })
        }
    }
};