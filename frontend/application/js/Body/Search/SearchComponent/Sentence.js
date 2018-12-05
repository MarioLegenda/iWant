class SentenceCreator {
    constructor(filters) {
        this.filters = filters;
    }

    constructSentence() {
        let finalSentence = ``;
        const filters = this.filters;

        if (filters.highQuality) {
            finalSentence += `of the highest quality `
        }

        if (filters.lowestPrice) {
            finalSentence += ` with the lowest prices showed first`
        }

        if (filters.highestPrice) {
            finalSentence += ` with the highest prices showed first`
        }

        if (filters.fixedPrice) {
            if (filters.highQuality || filters.lowestPrice || filters.highestPrice) {
                finalSentence += ` and with a fixed price.`;
            } else {
                finalSentence += ` with a fixed price.`;
            }

        }

        if (filters.shippingCountries.length > 0) {
            if (filters.shippingCountries[0].hasOwnProperty('worldwide')) {
                finalSentence += ` and I would like it to be shipped worldwide`;
            } else {
                let countryNames = [];
                for (const country of filters.shippingCountries) {
                    countryNames.push(country.name);
                }

                finalSentence += `. I would like it to be shipped to ${countryNames.join(', ')}.`;
            }
        }

        if (filters.taxonomies.length > 0) {
            if (filters.taxonomies.length === 1) {
                if (finalSentence.charAt(finalSentence.length - 1) !== '.') {
                    finalSentence += '.';
                }

                finalSentence += ` The search will only be done in the ${filters.taxonomies[0].name} category.`
            } else {
                let taxonomyNames = [];

                for (const marketplace of filters.taxonomies) {
                    taxonomyNames.push(marketplace.name);
                }

                if (finalSentence.charAt(finalSentence.length - 1) !== '.') {
                    finalSentence += '.';
                }

                if (taxonomyNames.length === 2) {
                    finalSentence += ` The search will be done in the ${taxonomyNames[0]} and ${taxonomyNames[1]} categories.`;
                } else {
                    finalSentence += ` The search will be done in ${taxonomyNames.join(', ')} categories.`;
                }
            }
        }

        return finalSentence;
    }
}

export const Sentence = {
    template: `
            <div v-if="showSentence" class="Sentence">
                <h1>You say:</h1>
                <p>I would like a <span class="highlighted">{{searchTerm}}</span> {{sentence}}</p>
            </div>
    `,
    props: ['sentenceData', 'showSentence'],
    watch: {
        getFilters: (prev, next) => {
        }
    },
    computed: {
        getFilters() {
            return this.$store.getters.getFilters;
        },

        sentence: function() {
            const sc = new SentenceCreator(this.getFilters);

            return sc.constructSentence();
        },

        searchTerm: function() {
            const {keyword} = this.sentenceData;

            if (keyword === null || keyword === '' || typeof keyword === 'undefined') {
                return '{ your search term }'
            }

            return keyword;
        }
    },
};