class SupportedSites {
    constructor(sites) {
        this.sites = sites;

        let len = 0;
        for (const site of this.sites) {
            if (site.enabled) {
                len++;
            }
        }

        this.enabledLength = len;
    }

    find(globalId) {
        for (const site of this.sites) {
            if (site.enabled) {
                if (site.globalId === globalId.toUpperCase()) {
                    return site;
                }
            }
        }

        throw new Error(`Global id ${globalId} not found as a supported site`);
    }

    tryFind(globalId) {
        for (const site of this.sites) {
            if (site.enabled) {
                if (site.globalId === globalId.toUpperCase()) {
                    return site;
                }
            }
        }

        return false;
    }

    has(globalId) {
        return this.tryFind(globalId);
    }
}

export const SUPPORTED_SITES = new SupportedSites([
    { globalId: 'EBAY-AT', icon: `/images/country_icons/ebay-at.svg`, enabled: true},
    { globalId: 'EBAY-DE', icon: `/images/country_icons/ebay-de.svg`, enabled: true},
    { globalId: 'EBAY-ES', icon: `/images/country_icons/ebay-es.svg`, enabled: true},
    { globalId: 'EBAY-FR', icon: `/images/country_icons/ebay-fr.svg`, enabled: true},
    { globalId: 'EBAY-FRBE', icon: `/images/country_icons/ebay-frbe.svg`, enabled: true},
    { globalId: 'EBAY-GB', icon: `/images/country_icons/ebay-gb.svg`, enabled: true},
    { globalId: 'EBAY-IT', icon: `/images/country_icons/ebay-it.svg`, enabled: true},
    { globalId: 'EBAY-US', icon: `/images/country_icons/ebay-us.svg`, enabled: true},
    { globalId: 'EBAY-IE', icon: `/images/country_icons/ebay-ie.svg`, enabled: true},
    { globalId: 'EBAY-PL', icon: `/images/country_icons/ebay-pl.svg`, enabled: true},
    { globalId: 'czech', icon: `/images/country_icons/ebay-pl.svg`, enabled: false},
]);