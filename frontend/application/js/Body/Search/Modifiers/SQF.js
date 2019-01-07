import {SAVED_STATE_MODE} from "../../../store/constants";

export const SQF = {
    data: function() {
        return {
            isToggleOpen: false,
            toggle: false,
        }
    },
    created() {
        if (this.getCurrentSearchStateMode === SAVED_STATE_MODE) {
            if (this.getFilters.searchQueryFilter === true) {
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
                    :labels="{checked: translationsMap.filters.sqfOn, unchecked: translationsMap.filters.sqfOff}"
                    :width="170"
                    :height="25">
                </toggle-button>
                
                    <v-popover class="TooltipContent">
                        <div class="fas fa-question TooltipContentButton"></div>
                    
                        <template slot="popover">
                            <div class="TooltipContent">
                                {{translationsMap.filters.sqfInformation}}
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

        getFilters() {
            return this.$store.getters.getFilters;
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
                searchQueryFilter: event.value,
            });
        }
    }
};