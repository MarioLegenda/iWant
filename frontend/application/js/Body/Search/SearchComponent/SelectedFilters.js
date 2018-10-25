export const SelectedFilters = {
    template: `<div v-if="areFiltersSelected" class="SelectedFilters">                   
                   <div
                        v-if="filtersEvent.lowestPrice"
                        class="SelectedFilter">Lowest price <i @click="removeFilter('lowestPrice')" class="fas fa-times"></i>
                   </div>
                   
                   <div
                        v-if="filtersEvent.highestPrice"
                        class="SelectedFilter">Highest price <i @click="removeFilter('highestPrice')" class="fas fa-times"></i>
                   </div>
                   
                   <div
                        v-if="filtersEvent.highQuality"
                        class="SelectedFilter">High quality <i @click="removeFilter('highQuality')" class="fas fa-times"></i>
                   </div>
               </div>`,
    computed: {
        filtersEvent: function() {
            return this.$store.state.filtersEvent;
        },
        areFiltersSelected: function() {
            const filtersEvent = this.filtersEvent;

            for (const evn in filtersEvent) {
                if (filtersEvent.hasOwnProperty(evn)) {
                    if (Array.isArray(filtersEvent[evn])) {
                        if (filtersEvent[evn].length > 0) {
                            return true;
                        }
                    }

                    if (isBoolean(filtersEvent[evn]) && filtersEvent[evn] === true) {
                        return true;
                    }
                }
            }

            return false;
        }
    },
    methods: {
        removeFilter(name) {
            let toRemove = {};

            toRemove[name] = false;

            this.$store.commit('filtersEvent', toRemove);
        }
    }
};