import Vue from "vue";
import {translationsMap} from "../translationMap";

export const actions = {
    localeChanged(context, locale) {
        Vue.prototype.$localeInfo.locale = locale.value;

        context.commit('translationsMap', translationsMap[locale.value]);
    },
};