import {LOWEST_PRICE, HIGHEST_PRICE} from "./constants";

export class Processor {
    constructor(view, added) {
        this.view = view;
        this.added = added;
        this.errors = [];

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

    addRangeFilter(id, value) {
    }

    addFilter(id) {
        this.resetValidation();
        this.correlationValidation(id);

        if (this.errors.length !== 0) {
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
            this.added.push(Object.assign({}, entries[0]));

            return true;
        }

        return false;
    }

    removeFilter(id) {
        this.resetValidation();

        for (let [index, entry] of this.added.entries()) {
            if (entry.id === id) {
                this.added.splice(index, 1);

                return true;
            }
        }

        return false;
    }

    resetValidation() {
        this.errors = [];
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
                    this.errors.push(`${lowestPriceNormalized} and ${highestPriceNormalized} filters cannot be used together`);
                }
            });
        }
    }
}