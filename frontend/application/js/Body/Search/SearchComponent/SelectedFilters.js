export const SelectedFilters = {
    template: `
        <transition name="fade">
            <div v-if="areFiltersSelected" class="SelectedFilters">
                <transition name="fade">
                    <div
                        v-if="filtersEvent.lowestPrice"
                        class="SelectedFilter">{{translationsMap.filters.lowestPriceFilter}} <i @click="removeFilter('lowestPrice')" class="fas fa-times"></i>
                    </div>
                </transition>
                   
                <transition name="fade">
                    <div
                        v-if="filtersEvent.highestPrice"
                        class="SelectedFilter">{{translationsMap.filters.highestPriceFilter}} <i @click="removeFilter('highestPrice')" class="fas fa-times"></i>
                   </div>
                </transition>
                   
                <transition name="fade">
                    <div
                        v-if="filtersEvent.highQuality"
                        class="SelectedFilter">{{translationsMap.filters.highQualityFilter}} <i @click="removeFilter('highQuality')" class="fas fa-times"></i>
                   </div>
                </transition>
                
                <transition name="fade">
                    <div
                        v-if="filtersEvent.fixedPrice"
                        class="SelectedFilter">{{translationsMap.filters.fixedPriceFilter}} <i @click="removeFilter('fixedPrice')" class="fas fa-times"></i>
                   </div>
                </transition>
                
                <transition name="fade">
                    <div
                        v-if="hasShippingCountry(filtersEvent.shippingCountries)"
                        class="SelectedFilter">{{shippingCountryTitle}} <i @click="removeFilter('shippingCountries')" class="fas fa-times"></i>
                   </div>
                </transition>
             </div>
        </transition>`,
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

                    if (evn === 'shippingCountries') {
                        if (!isEmpty(filtersEvent[evn])) {
                            return true;
                        }
                    }
                }
            }

            return false;
        },

        shippingCountryTitle: function() {
            const shippingCountry = this.filtersEvent.shippingCountries;
            const country = this.$countries.find(function(element) {
                if (shippingCountry[0] === element.alpha2Code) {
                    return true;
                }

                return false;
            });

            return `${this.translationsMap.filters.shippingCountryFilterTitle} ${country.name}`
        },

        translationsMap: function() {
            return this.$store.state.translationsMap;
        },
    },
    methods: {
        hasShippingCountry: function(shippingCountries) {
            return !isEmpty(shippingCountries);
        },

        removeFilter(name) {
            let toRemove = {};

            if (name === 'shippingCountries') {
                toRemove[name] = [];
            } else {
                toRemove[name] = false;
            }

            this.$store.commit('filtersEvent', toRemove);
        }
    }
};