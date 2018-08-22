export class Processor {
    constructor(view, added) {
        this.view = view;
        this.added = added;
        this.errors = [];
    }

    isFilterAdded(id) {
        let alreadyAdded = this.added.filter(entry => {
            if (entry.id === id) {
                return entry;
            }
        });

        return alreadyAdded.length !== 0
    }

    addFilter(id) {
        this.genericValidation(id);

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
        for (let [index, entry] of this.added.entries()) {
            if (entry.id === id) {
                this.added.splice(index, 1);

                return true;
            }
        }

        return false;
    }

    genericValidation(id) {
        const mappings = {
            1: [2],
            2: [1],
        };

        if (mappings.hasOwnProperty(id)) {
            const mappedProp = mappings[id];

            mappedProp.filter(entry => {
                if (this.isFilterAdded(entry)) {
                }
            });
        }
    }
}