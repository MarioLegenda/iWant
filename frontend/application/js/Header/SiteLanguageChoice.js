export const SiteLanguageChoice = {
    data: function() {
        return {
            selected: { label: 'English (default)', value: 'en', icon: '/images/country_icons/ebay-gb.svg' },
            placeholder: "type or select",
            options: [
                { label: 'English (default)', value: 'en', icon: '/images/country_icons/ebay-gb.svg' },
                { label: 'Spanish', value: 'es', icon: '/images/country_icons/ebay-es.svg' },
                { label: 'French', value: 'fr', icon: '/images/country_icons/ebay-fr.svg' },
                { label: 'Italian', value: 'it', icon: '/images/country_icons/ebay-it.svg' },
                { label: 'Irish', value: 'ga', icon: '' },
            ]
        }
    },
    template: `
            <div class="SiteLanguageChoice">
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
        }
    },
};