export class GlobalIdInformation {
    constructor(info) {

        this.all = info;
        this.normalizedGlobalIds = Object.entries(this.all).map((a) => a[0].toUpperCase());
    }
}