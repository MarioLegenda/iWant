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

        if (filters.marketplaces.length > 0) {
            if (filters.marketplaces.length === 1) {
                if (finalSentence.charAt(finalSentence.length - 1) !== '.') {
                    finalSentence += '.';
                }

                finalSentence += ` Also, search only in the ${filters.marketplaces[0].name} marketplace.`
            } else {
                let marketplaceNames = [];

                for (const marketplace of filters.marketplaces) {
                    marketplaceNames.push(marketplace.name);
                }

                if (finalSentence.charAt(finalSentence.length - 1) !== '.') {
                    finalSentence += '.';
                }

                if (marketplaceNames.length === 2) {
                    finalSentence += ` Also, search only in ${marketplaceNames[0]} and ${marketplaceNames[1]}.`;
                } else {
                    finalSentence += ` Also, search only in ${marketplaceNames.join(', ')} marketplaces.`;
                }
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
    computed: {
        sentence: function() {
            const {filters} = this.sentenceData;

            const sc = new SentenceCreator(filters);

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