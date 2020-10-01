import Vue from 'vue';
import VueI18n from 'vue-i18n';
import languageBundle from '@kirschbaum-development/laravel-translations-loader!@kirschbaum-development/laravel-translations-loader';

Vue.use(VueI18n);

export const i18n = new VueI18n({
    locale: window.locale,
    messages: languageBundle,
});