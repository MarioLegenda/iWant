export class GlobalIdInformation {
    constructor(info) {

        this.globalIdInformation = info;
    }
}

GlobalIdInformation.install = function(Vue, options) {
    return new GlobalIdInformation(options.globalIdInformation);
};