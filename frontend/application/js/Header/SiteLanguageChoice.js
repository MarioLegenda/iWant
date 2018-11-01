import {SUPPORTED_SITES} from "../global";

export const SiteLanguageChoice = {
    data: function() {
        return {
            selected: { label: 'English (default)', value: 'en', icon: '/images/country_icons/ebay-gb.svg' },
            placeholder: "Select a language",
            options: [
                { label: 'English (default)', value: 'en', icon: SUPPORTED_SITES.find('ebay-gb').icon },
                { label: 'Spanish', value: 'es', icon: SUPPORTED_SITES.find('ebay-es').icon },
                { label: 'French', value: 'fr', icon: SUPPORTED_SITES.find('ebay-fr').icon },
                { label: 'Italian', value: 'it', icon: SUPPORTED_SITES.find('ebay-it').icon },
                { label: 'Irish', value: 'ga', icon: SUPPORTED_SITES.find('ebay-ie').icon },
                { label: 'Polish', value: 'pl', icon: SUPPORTED_SITES.find('ebay-pl').icon },
            ]
        }
    },
    template: `
            <div class="SiteLanguageChoice">
                <span class="Title">All products searched will be translated to the language you select</span>
                <v-select 
                    v-model="selected" 
                    :options="options"
                    v-on:input="onInputChange"
                    label="label"
                    :placeholder="placeholder">
                    
                    <template slot="option" slot-scope="option">
                        <img :src="option.icon" class="LanguageImage">
                        {{option.label}}
                    </template>
                </v-select>
            </div>`,
    methods: {
        onInputChange(val) {
            this.selected = val;

            this.$store.dispatch('localeChanged', this.selected.value);
        }
    },
};