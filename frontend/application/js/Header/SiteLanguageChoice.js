import {supportedLanguages} from "../global/mixins";

export const SiteLanguageChoice = {
    mixins: [supportedLanguages],
    data: function() {
        return {
            selected: { label: 'English (default)', value: 'en', icon: '/images/country_icons/ebay-gb.svg' },
        }
    },
    watch: {
        localeChanged: function(prev, next) {}
    },
    computed: {
        localeChanged: function() {
            const localeChanged = this.$store.state.localeChanged;

            if (localeChanged === null || typeof localeChanged === 'undefined') {
                return;
            }

            this.selectLocale(localeChanged.value);

            return localeChanged;
        },
        translationsMap: function() {
            return this.$store.state.translationsMap;
        }
    },
    template: `
            <div class="SiteLanguageChoice">
                <modal
                    name="site-language-choice-modal"
                    :width="480"
                    :minHeight="500"
                    :scrollable="true"
                    height="auto">
                    
                    <div class="LanguageChoiceModalWrapper">
                        <p 
                            class="LanguageChoice"
                            v-for="item in languages" 
                            :key="item.value" 
                            @click="onSiteLanguageChosen(item.value)">
                            {{item.label}}
                            
                            <img :src="item.icon" />
                        </p>
                    </div>
                </modal>
                
                <span class="Explanation">{{translationsMap.languageChoiceExplanation}}</span>
                <p @click="openLanguageModal" class="SelectedLanguage">{{selected.label}}<img :src="selected.icon" /></p>
            </div>`,
    methods: {
        openLanguageModal() {
            this.$modal.show('site-language-choice-modal');
        },

        onSiteLanguageChosen(locale) {
            window.CookieHandler.eraseCookie('SiteLanguage');
            window.CookieHandler.createCookie('SiteLanguage', locale);

            this.refresh(locale);
        },

        selectLocale(locale) {
            for (let i = 0; i < this.languages.length; i++) {
                if (this.languages[i].value === locale) {
                    this.selected = this.languages[i];

                    break;
                }
            }
        },

        refresh(locale) {
            const paths = location.pathname.split('/').splice(2);

            paths.unshift(locale);

            const path = `/${paths.join('/')}`;

            location.href = path;
        }
    },
};