import {SUPPORTED_SITES} from "../supportedSites";

export const SiteLanguageChoice = {
    data: function() {
        return {
            selected: { label: 'English (default)', value: 'en', icon: '/images/country_icons/ebay-gb.svg' },
            languages: [
                { label: 'English (default)', value: 'en', icon: SUPPORTED_SITES.find('ebay-gb').icon },
                { label: 'German', value: 'de', icon: SUPPORTED_SITES.find('ebay-de').icon },
                { label: 'Spanish', value: 'es', icon: SUPPORTED_SITES.find('ebay-es').icon },
                { label: 'French', value: 'fr', icon: SUPPORTED_SITES.find('ebay-fr').icon },
                { label: 'Italian', value: 'it', icon: SUPPORTED_SITES.find('ebay-it').icon },
                { label: 'Irish', value: 'ga', icon: SUPPORTED_SITES.find('ebay-ie').icon },
                { label: 'Polish', value: 'pl', icon: SUPPORTED_SITES.find('ebay-pl').icon },
                { label: 'Czech', value: 'cs', icon: SUPPORTED_SITES.findAny('czech').icon },
                { label: 'Slovak', value: 'sk', icon: SUPPORTED_SITES.findAny('slovakia').icon },
            ]
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