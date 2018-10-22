export class SelectedItems {
    constructor() {
        this.items = [];
    }

    length() {
        let len = 0;
        for (let item in this.items) {
            len++;
        }

        return len;
    }

    normalize() {
        let normalized = [];
        for (let item of this.items) {
            if (typeof item !== 'undefined') {
                normalized.push(item);
            }
        }

        return normalized;
    }

    clear() {
        this.items = [];
    }

    remove(index) {
        this.items.splice(index, 1);
    }

    add(item) {
        this.items[item.index] = item;
    }
}