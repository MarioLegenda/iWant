import {SAVED_STATE_MODE} from "../../../store/constants";

export const DoubleLocaleSearch = {
    data: function() {
        return {
            isToggleOpen: false,
            toggle: false,
        }
    },
    created() {
        console.log(this.getCurrentSearchStateMode);
        if (this.getCurrentSearchStateMode === SAVED_STATE_MODE) {
            const filtersEvent = this.$store.state.filtersEvent;

            if (filtersEvent.doubleLocaleSearch === true) {
                this.toggle = true;
            }
        }
    },
    template: `
        <div class="ModifierWrapper">
            <div class="Modifier">
                <toggle-button
                    :value="toggle"
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

        getCurrentSearchStateMode: function() {
            return this.$store.getters.getCurrentSearchStateMode;
        }
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