

import { THEME_DARK_STORAGE } from '@/app/config'
import { mapActions } from 'vuex'

const flipChoice = (data) => {
  const f = {}
  for (let a in data) {
    let c
    c = data[a].split('|')
    c = [c[1], c[0]].join('|')
    f[a] = c

  }
  // console.log(f);
  // console.log(JSON.stringify(f));
  return f
}
export default {
  metaInfo () {
    const AppName = process.env.MIX_APP_NAME
    return {
      title: AppName,
      titleTemplate: `${AppName} | %s`
    }
  },
  data () {
    return {
      IniApp: false,
      AppProgress: false,
      AppFullscreen: false,
      iniAxios: {},
      AxiosCountdown: 100,
      showAxiosErrorMessage: !0
    }
  },
  methods: {
    ...mapActions('auth', {
      iniAuthFromStorage: 'iniAuthFromStorage',
      refreshUser: 'refresh'
    }),
    updateProfile (user = null) {
      if (user)
        return new Promise((resolve, reject) => {
          this.authUser = user
          resolve(user)
          return user
        })
      else
        return this.$api.methods.user.refreshProfile().then(({ data }) => {
          if (data && data.data && data.success === true) {
            this.authUser = data.data
            return data.data
          }
          return {}
        })
    },
    iniTheme () {
      this.themeDark = parseInt(localStorage.getItem(THEME_DARK_STORAGE)) === 1
    }
  },
  beforeCreate () {
    this.$axios.interceptors.response.use((response) => response, (error) => {
      const res = () => {
        this.$root.showAxiosErrorMessage = !0
        return Promise.reject(error)
      }
      if (!error || !error.response)
        return res()

      if (this.$root.showAxiosErrorMessage && error.response.status === 422 && error.responseError && error.responseError.message) {
        this.$nextTick(() => this.alertError(error.responseError.message))
      }

      return res()
    })
  },
  async beforeMount () {
    await this.iniAuthFromStorage()
  },
  created () {
    this.IniAppLocale()
    this.iniTheme()
  }

}
