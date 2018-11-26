export const HideDuplicateItems = {
    template: `
        <div class="SingleFilterWrapper">
            <toggle-button
                id="changed-font"
                :sync="true" 
                @change="onChange" 
                :labels="{checked: 'Hide duplicates', unchecked: 'Show duplicates'}"
                :width="150"
                :height="40"></toggle-button>
                
            <div class="fas fa-question TooltipContent" v-tooltip="translationsMap.filters.hideDuplicateItemsExplanation"></div>
        </div>
    `,
    computed: {
        translationsMap: function() {
            return this.$store.state.translationsMap;
        },
    },
    methods: {
        onChange: function(event) {
            this.$store.commit('filtersEvent', {
                hideDuplicateItems: event.value,
            });
        }
    }
};