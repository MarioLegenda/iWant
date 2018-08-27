export class Errors {
    constructor(errors) {
        this.errors = errors;
        this.errorKeys = [];

        for (const error in this.errors) {
            this.errorKeys.push(error);
        }
    }

    reset() {
        for (const errorKey of this.errorKeys) {
            this.errors[errorKey] = false;
        }
    }

    addError(error, message) {
        if (this.errors.hasOwnProperty(error)) {
            this.errors[error] = message;
        }
    }

    hasError(error) {
        return this.errors.hasOwnProperty(error) && this.errors[error] !== false
    }

    hasErrors() {
        let hasErrors = false;
        for (const errorKey of this.errorKeys) {
            if (this.errors[errorKey] !== false) {
                hasErrors = true;
            }
        }

        return hasErrors;
    }

    removeError(error) {
        this.errors[error] = false;
    }
}