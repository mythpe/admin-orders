

import Vue from '@plugins/vue'
import Vuetify from 'vuetify/lib'
import i18n, { locales } from '@plugins/vue-i18n'
import { defaultLocale, rtl } from '@/app/config'

Vue.use(Vuetify)

const opts = {
  rtl,
  theme: {
    dark: !0,
    options: {
      customProperties: !0
    },
    themes: {
      light: {
        // primary: "#2196f3",
        // primary: "#0D47A1",
        primary: '#0277BD',
        secondary: '#607d8b',
        accent: '#009688'
      },
      dark: {
        primary: '#0277BD',
        // secondary: "#0097A7",
        secondary: '#00838F',
        accent: '#009688'
      }
    }
  },
  lang: {
    current: defaultLocale,
    defaultLocale,
    t: (key, ...params) => i18n.t(key, params),
    locales
  }

}

export default new Vuetify(opts)
