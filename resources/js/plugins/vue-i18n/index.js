
import Vue from '@plugins/vue'
import VueI18n from 'vue-i18n'
import { locales, messages } from './register'
import { defaultLocale } from '@/app/config'

VueI18n.availableLocales = locales

Vue.use(VueI18n)

const options = {
  locale: defaultLocale,
  fallbackLocale: defaultLocale,
  messages
}
const i18n = new VueI18n(options)

export default i18n

export { messages, i18n, locales }
