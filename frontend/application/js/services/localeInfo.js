export class LocaleInfo {
    constructor(locale, fallbackLocale = 'en') {
        this.locale = locale;
        this.fallbackLocale = fallbackLocale;
    }
}