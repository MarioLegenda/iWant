import {SUPPORTED_SITES} from "../supportedSites";

export const supportedLanguages = {
    data: function() {
        return {
            languages: [
                { label: 'English (default)', value: 'en', icon: SUPPORTED_SITES.find('ebay-gb').icon },
                { label: 'German', value: 'de', icon: SUPPORTED_SITES.find('ebay-de').icon },
                { label: 'Spanish', value: 'es', icon: SUPPORTED_SITES.find('ebay-es').icon },
                { label: 'French', value: 'fr', icon: SUPPORTED_SITES.find('ebay-fr').icon },
                { label: 'Italian', value: 'it', icon: SUPPORTED_SITES.find('ebay-it').icon },
                { label: 'Irish', value: 'ga', icon: SUPPORTED_SITES.find('ebay-ie').icon },
                { label: 'Polish', value: 'pl', icon: SUPPORTED_SITES.find('ebay-pl').icon },
                { label: 'Czech', value: 'cs', icon: SUPPORTED_SITES.findAny('czech').icon },
                { label: 'Slovak', value: 'sk', icon: SUPPORTED_SITES.findAny('slovakia').icon },
            ],
        }
    },
};