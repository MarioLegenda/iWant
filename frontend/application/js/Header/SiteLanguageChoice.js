import {SUPPORTED_SITES} from "../supportedSites";

export const SiteLanguageChoice = {
    data: function() {
        return {
            selected: { label: 'English (default)', value: 'en', icon: '/images/country_icons/ebay-gb.svg' },
            placeholder: "Select a language",
            options: [
                { label: 'English (default)', value: 'en', icon: SUPPORTED_SITES.find('ebay-gb').icon },
                { label: 'German', value: 'de', icon: SUPPORTED_SITES.find('ebay-de').icon },
                { label: 'Spanish', value: 'es', icon: SUPPORTED_SITES.find('ebay-es').icon },
                { label: 'French', value: 'fr', icon: SUPPORTED_SITES.find('ebay-fr').icon },
                { label: 'Italian', value: 'it', icon: SUPPORTED_SITES.find('ebay-it').icon },
                { label: 'Irish', value: 'ga', icon: SUPPORTED_SITES.find('ebay-ie').icon },
                { label: 'Polish', value: 'pl', icon: SUPPORTED_SITES.find('ebay-pl').icon },
                { label: 'Czech', value: 'cs', icon: SUPPORTED_SITES.findAny('czech').icon },
                { label: 'Slovakian', value: 'sk', icon: SUPPORTED_SITES.findAny('slovakia').icon },
            ]
        }
    },
    computed: {
        localeChanged: function() {
            const localeChanged = this.$store.state.localeChanged;

            this.selectLocale(this.$localeInfo.locale);

            return localeChanged;
        },
        translationsMap: function() {
            return this.$store.state.translationsMap;
        }
    },
    template: `
            <div class="SiteLanguageChoice">
                <input type="hidden" :value="localeChanged" />
                <span class="Title">{{translationsMap.languageChoiceExplanation}}</span>
                <v-select
                    :value="selected"
                    :options="options"
                    @input="onInputChange"
                    label="label"
                    :placeholder="placeholder">
                    
                    <template slot="option" slot-scope="option">
                        <img :src="option.icon" class="LanguageImage">
                        {{option.label}}
                    </template>
                </v-select>
            </div>`,
    methods: {
        onInputChange(locale) {
            if (locale.value === this.selected.value) {
                return;
            }

            this.refresh(locale);
        },
        selectLocale(locale) {
            for (let i = 0; i < this.options.length; i++) {
                if (this.options[i].value === locale) {
                    this.selected = this.options[i];

                    break;
                }
            }
        },
        refresh(locale) {
            const paths = location.pathname.split('/').splice(2);

            paths.unshift(locale.value);

            const path = `/${paths.join('/')}`;

            location.href = path;
        }
    },
};