import {LOWEST_PRICE, HIGHEST_PRICE} from "./constants";
import {Errors} from "./error";

export class Processor {
    constructor(view, added, customErrors) {
        this.view = view;
        this.added = added;
        this.errors = new Errors(customErrors);

        this.normalizedFilters = {
            1: { type: LOWEST_PRICE, normalized: 'Lowest price'},
            2: { type: HIGHEST_PRICE, normalized: 'Highest price'},
        }
    }

    isFilterAdded(id) {
        let alreadyAdded = this.added.filter(entry => {
            if (entry.id === id) {
                return entry;
            }
        });

        return alreadyAdded.length !== 0
    }

    upsertShipsToCountry(id, country) {
        this.errors.reset();

        let alreadyAdded = this.added.filter(entry => {
            if (entry.id === id) {
                return entry;
            }
        });

        if (alreadyAdded.length !== 0) {
            const entry = alreadyAdded[0];
            entry.data = {
                filterType: entry.filterType,
                data: country
            };

            entry.text = `Ships to ${country.name}`;

            return;
        }

        let entries = this.view.filter(entry => {
            if (entry.id === id) {
                return entry;
            }
        });

        const countryView = entries[0];

        const countryFilter = Object.assign({}, countryView);
        countryFilter.data = {
            filterType: countryView.filterType,
            data: country,
        };

        countryFilter.text = `Ships to ${country.name}`;

        this.added.push(countryFilter);
    }

    upsertRangeFilter(id, value) {
        this.errors.reset();

        let entries = this.view.filter(entry => {
            if (entry.id === id) {
                return entry;
            }
        });

        const from = (value.minPrice !== null) ? `from ${value.minPrice}$` : '';
        const to = (value.maxPrice !== null) ? `up to ${value.maxPrice}$` : '';

        const text = `Price ${from} ${to}`;

        let alreadyAdded = this.added.filter(entry => {
            if (entry.id === id) {
                return entry;
            }
        });

        if (alreadyAdded.length !== 0) {
            const entry = alreadyAdded[0];

            entry.data = {
                filterType: entry.filterType,
                data: {
                    minPrice: value.minPrice,
                    maxPrice: value.maxPrice,
                }
            };

            entry.text = text;

            return;
        }

        const entry = entries[0];
        const filterType = entry.filterType;

        entry.data = {
            filterType: filterType,
            data: {
                minPrice: entry.minPrice,
                maxPrice: entry.maxPrice,
            }
        };

        const rangeFilter = Object.assign({}, entries[0]);
        rangeFilter.text = text;

        this.added.push(rangeFilter);
    }

    addGenericFilter(id) {
        this.correlationValidation(id);

        if (this.errors.hasErrors()) {
            return false;
        }

        let alreadyAdded = this.added.filter(entry => {
            if (entry.id === id) {
                return entry;
            }
        });

        if (alreadyAdded.length !== 0) {
            return false;
        }

        let entries = this.view.filter(entry => {
            if (entry.id === id) {
                return entry;
            }
        });

        if (entries.length === 1) {
            const entry = entries[0];
            const filterType = entry.filterType;
            entry.data = {
                filterType: filterType,
                data: []
            };

            this.added.push(Object.assign({}, entry));

            return true;
        }

        return false;
    }

    removeFilter(id) {
        this.errors.reset();

        for (let [index, entry] of this.added.entries()) {
            if (entry.id === id) {
                this.added.splice(index, 1);

                return true;
            }
        }

        return false;
    }

    correlationValidation(id) {
        const mappings = {
            1: [2],
            2: [1],
        };

        if (mappings.hasOwnProperty(id)) {
            const mappedProp = mappings[id];

            mappedProp.filter(entry => {
                if (this.isFilterAdded(entry)) {
                    let lowestPriceNormalized = this.normalizedFilters[1].normalized;
                    let highestPriceNormalized = this.normalizedFilters[2].normalized;
                    this.errors.addError('lowestHighestPrice', `${lowestPriceNormalized} and ${highestPriceNormalized} filters cannot be used together. Use one or the other.`);
                }
            });
        }
    }
}