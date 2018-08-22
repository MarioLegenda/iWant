export class BasicException {
    constructor(array) {
        if (Array.isArray(array)) {
            throw new Error('BasicException can only accepts an array of strings');
        }

        this.messages = array;
    }
}