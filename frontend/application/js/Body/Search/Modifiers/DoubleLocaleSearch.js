export const DoubleLocaleSearch = {
    data: function() {
        return {
            isToggleOpen: false,
        }
    },
    template: `
        <div class="ModifierWrapper">
            <div class="Modifier">
                <toggle-button
                    id="changed-font"
                    :sync="true"
                    @change="onChange"
                    :labels="{checked: translationsMap.filters.doubleLocaleSearchShow, unchecked: translationsMap.filters.doubleLocaleSearchHide}"
                    :width="170"
                    :height="25">
                </toggle-button>
                
                    <v-popover class="TooltipContent">
                        <div class="fas fa-question TooltipContentButton"></div>
                    
                        <template slot="popover">
                            <div class="TooltipContent">
                                {{translationsMap.filters.doubleLocaleSearchExplanation}}
                            </div>
                        
                        </template>
                    </v-popover>
            </div>
        </div>
    `,
    computed: {
        translationsMap: function() {
            return this.$store.state.translationsMap;
        },
    },
    methods: {
        openInformation: function() {
            this.isToggleOpen = !this.isToggleOpen;
        },
        onChange: function(event) {
            this.$store.commit('filtersEvent', {
                doubleLocaleSearch: event.value,
            });
        }
    }
};