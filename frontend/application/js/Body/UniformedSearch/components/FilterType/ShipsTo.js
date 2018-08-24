export const ShipsTo = {
    data: function() {
        return {
            countries: [],
            selected: '',
        }
    },
    template: `<div class="ships-to-filter">
                   <span>Ships to </span>
                   
                   <select v-on:change="countrySelected" v-model="selected">
                       <option disabled selected value="">Select country</option>
                       <option v-for="country in countries" :value="country">{{country.name}}</option>
                   </select>
               </div>`,
    created: function() {
        fetch('https://restcountries.eu/rest/v2/all')
            .then(function(response) {
                return response.json();
            })
            .then(countries => {
                for (const entry of countries) {
                    let country = {
                        name: entry.name,
                        alpha3Code: entry.alpha3Code,
                        flag: entry.flag
                    };

                    this.countries.push(country);
                }
            });
    },
    methods: {
        countrySelected: function() {
            this.$emit('search-on-ships-to', this.selected);
        }
    }
};