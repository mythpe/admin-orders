import { defaultLocale, LOCALE_STORAGE_KEY } from '@/app/config'
import { localize } from '@plugins/vee-validate'
import { locales } from '@plugins/vue-i18n/register'
import { camelCase, isString } from 'lodash'
import { mapActions, mapGetters, mapMutations } from 'vuex'

const defaultAxiosCountDown = 310
export default {
  methods: {
    IsAppLocale(locale) {
      return this.AppLocales.find(l => l.code === locale)
    },
    SetAppLocale(locale) {
      this.AppLocale = locale
      this.$nextTick(() => window.location.reload())
    },
    IniAppLocale() {
      this.AppLocale = localStorage.getItem(LOCALE_STORAGE_KEY) || defaultLocale
    },
    getPageTitle() {
      const t = (...a) => this.$t(...a)
      const tc = (...a) => this.$tc(...a)
      const e = (...a) => this.$te(...a)
      const _ = this.$_
      let str = ''

      let { name, pageName } = this.$options || {}
      let routePath = this.$route.path.split('/').pop()
      let routeName = this.$route.name.split('/').pop()

      if (routePath && e(routePath) && isString((str = t(routePath)))) {
        return str
      }

      routePath = camelCase(routePath)
      if (routePath && e(routePath) && isString((str = t(routePath)))) {
        return str
      }

      if (routeName && e(routeName) && isString((str = t(routeName)))) {
        return str
      }

      routeName = camelCase(routeName)
      if (routeName && e(routeName) && isString((str = t(routeName)))) {
        return str
      }

      let $routes

      $routes = `$routes.${routeName}`
      if (routeName && e($routes) && isString((str = t($routes)))) {
        return str
      }

      let pluralize = _.pluralize(_.upperFirst(routeName.split('Index').join('').split('index').join('')))
      $routes = `choice.${pluralize}`

      if ($routes && e($routes) && isString((str = tc($routes, 2)))) {
        return str
      }
      // console.log($routes)
      if (e(`${name}.name`) && isString((str = t(`${name}.name`)))) {
        return str
      }

      if (e(name) && isString((str = t(name)))) {
        return str
      }

      name = camelCase(name)
      if (e(`${name}.name`) && isString((str = t(`${name}.name`)))) {
        return str
      }

      if (e(name) && isString((str = t(name)))) {
        return str
      }

      if (e(pageName) && isString((str = t(pageName)))) {
        return str
      }

      return ''
    },
    parseAttribute(string, ...args) {
      if (!string) return string

      const _ = this.$_, t = (...a) => this.$t(...a), te = (...a) => this.$te(...a)

      let key = (this.$helpers.isOnlyObject(string) ? (string.text || '') : string).toString()

      if (te(key) && isString(t(key)))
        return t(key, ...args)

      if (te(`attributes.${key}`) && isString(t(`attributes.${key}`)))
        return t(`attributes.${key}`, ...args)

      return string
    },

    getAxiosName() {
      return this.$options.name
    },
    getAxiosItems() {
      return this.getIniAxios(this.getAxiosName())
    },
    setIniAxios(name, v) {
      this.$root.iniAxios[name] = v
    },
    getIniAxios(name) {
      return null
    },
    increaseAxios(countdown = defaultAxiosCountDown) {
      countdown = parseInt(countdown) || defaultAxiosCountDown
      this.$root.AxiosCountdown += countdown
      // console.log(this.$root.AxiosCountdown);
      return this.getAxiosCountdown()
    },
    setAxiosCountdown(countdown = defaultAxiosCountDown) {
      this.$root.AxiosCountdown = countdown
    },
    getAxiosCountdown() {
      return this.$root.AxiosCountdown
    },
    queueAxios(func, countdown = defaultAxiosCountDown) {
      // console.log(countdown);
      setTimeout(() => setTimeout(() => this.$nextTick(func), this.increaseAxios(countdown)), 60)
    },
    getOpenFieldName(value) {
      return this.openFieldsSelect.find(e => e.value === value)?.text
    }
  },
  computed: {
    APP_DEBUG: () => process.env.NODE_ENV === 'development',
    DefaultAppLocale: () => defaultLocale,
    AppRtl: {
      set(v) {
        this.$root.$vuetify.rtl = v
      },
      get() {
        const { rtl } = this.$root.$vuetify || {}
        return rtl !== undefined ? rtl : true
      }
    },
    AppDirection: {
      set(v) {
        this.AppRtl = v.toLowerCase() === 'rtl'
      },
      get() {
        return this.AppRtl ? 'rtl' : 'ltr'
      }
    },
    AppAlign: {
      set(v) {
        this.AppRtl = v.toLowerCase() === 'right'
      },
      get() {
        return this.AppRtl ? 'right' : 'left'
      }
    },
    AppLocales() {
      return locales.map(code => ({ title: this.$t(code), code }))
    },
    AppLocale: {
      set(locale = defaultLocale) {
        locale = ('' + locale).toString().toLocaleLowerCase()

        !this.IsAppLocale(locale) && (locale = defaultLocale)

        localStorage.setItem(LOCALE_STORAGE_KEY, locale)
        document.documentElement.setAttribute('lang', locale)
        document.documentElement.setAttribute('dir', locale === 'ar' ? 'rtl' : 'ltr')

        // Vuetify
        this.$root.$i18n.locale = locale
        this.$root.$vuetify.lang.current = locale
        this.$root.$vuetify.rtl = locale === 'ar'

        // axios
        this.$axios.defaults.headers.common['Locale'] = locale

        // Vuex
        const validations = require(`@locales/${locale}/validations.js`).default
        localize(locale, validations.default || validations)

        // moment js
        this.$moment.locale(locale)
      },
      get() {
        return this.$root.$vuetify.lang.current
      }
    },
    themeDark: {
      get() {
        return Boolean(mapGetters('auth', ['themeDark']).themeDark.call(this))
      },
      set(value) {
        this.$vuetify.theme.dark = value
        mapActions('auth', ['setThemeDark']).setThemeDark.call(this, value)
      }
    },
    themeLight: {
      get() {
        // console.log(this.themeDark)
        return !Boolean(this.themeDark)
      },
      set(value) {
        value = !value
        this.$vuetify.theme.dark = value
        mapActions('auth', ['setThemeDark']).setThemeDark.call(this, value)
      }
    },
    themeColor() {
      return this.themeDark ? 'dark' : 'light'
    },
    AppIsMobile() {
      return this.$root.$vuetify.breakpoint.smAndDown || false
    },

    authUser: {
      set(user) {
        mapMutations('auth', ['setUser']).setUser.call(this, user)
      },
      get() {
        return mapGetters('auth', ['getUser']).getUser.call(this)
      }
    },
    apiService() {
      return this.$api.methods
    },
    isAdmin() {
      return this.authUser?.role_name === 'admin'
    }
  },
  data() {
    const colors = ['red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'blue-grey', 'grey']
    return {
      userColorsSelect: colors.map(value => ({ value, text: value })),
      openFieldsSelect: [
        {
          text: 'OPNSM',
          value: 1
        },
        {
          text: 'OPNSB',
          value: 2
        }
      ]
    }
  }
}
