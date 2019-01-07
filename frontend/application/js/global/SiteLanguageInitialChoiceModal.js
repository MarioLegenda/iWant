import {supportedLanguages} from "./mixins";

export const SiteLanguageInitialChoiceModal = {
    mixins: [supportedLanguages],
    data: function() {
        return {
            defaultLocale: 'en',
            chosenLanguage: null,
        }
    },
    template: `
                <div class="SiteLanguageInitialChoiceWrapper">
                    <modal
                        name="site-language-initial-choice-modal"
                        height="auto"
                        :width="480"
                        :minHeight="500"
                        :clickToClose="false"
                        :scrollable="true">
                        
                        <div class="SiteLanguageChoiceModal">
                            <p class="ChoiceInformation">
                                The application translates every piece of information fetched from 
                                any eBay site and translates it to the language of your choice. For example,
                                if you choose to search eBay Germany, all the German language items will be translated
                                to the language of your choice. If you don't choice any language, English will be 
                                the site language by default.
                            </p>
                            
                            <div class="LanguageChoiceModalWrapper" id="Hack_ClearHoverable">
                                <p 
                                    class="LanguageChoice"
                                    v-for="item in languages" 
                                    :key="item.value" 
                                    @click="onSiteLanguageChosen(item.value, $event)">
                                    {{item.label}}
                            
                                    <img :src="item.icon" />
                                </p>
                            </div>
                            
                            <div class="ActionContainer">
                                <button @click="onAfterChosen">Ok</button>
                            </div>
                        </div>
                    </modal>
                </div>
    `,
    methods: {
        onSiteLanguageChosen(locale, $event) {
            const target = $event.target;

            const hoverableElements = document.getElementById('Hack_ClearHoverable').getElementsByTagName('p');

            for (let i = 0; i < hoverableElements.length; i++) {
                const elem = hoverableElements[i];

                elem.classList.remove('ChosenLanguage');
            }

            target.classList.add('ChosenLanguage');

            this.chosenLanguage = locale;
        },

        onAfterChosen() {
            if (isEmpty(this.chosenLanguage)) {
                this._addLocaleCookie(this.defaultLocale);

                this._refresh(this.defaultLocale);

                return;
            }

            this._addLocaleCookie(this.chosenLanguage);

            this._refresh(this.chosenLanguage);
        },

        _addLocaleCookie(locale) {
            const cookieHandler = window.CookieHandler;

            cookieHandler.eraseCookie('SiteLanguage');
            cookieHandler.createCookie('SiteLanguage', locale);
        },

        _refresh(locale) {
            const paths = location.pathname.split('/').splice(2);

            paths.unshift(locale);

            const path = `/${paths.join('/')}`;

            location.href = path;
        }
    }
};