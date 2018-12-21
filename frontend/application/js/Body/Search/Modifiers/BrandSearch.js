import {SAVED_STATE_MODE} from "../../../store/constants";

export const BrandSearch = {
    data: function() {
        return {
            isToggleOpen: false,
            toggle: true,
        }
    },
    created() {
        if (this.getCurrentSearchStateMode === SAVED_STATE_MODE) {
            const filtersEvent = this.$store.state.filtersEvent;

            if (filtersEvent.brandSearch === true) {
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
                    :labels="{checked: translationsMap.filters.brandSearchShow, unchecked: translationsMap.filters.brandSearchHide}"
                    :width="170"
                    :height="25">
                </toggle-button>
                
                <div class="fas fa-question TooltipContentButton"></div>
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
                brandSearch: event.value,
            });
        }
    }
};