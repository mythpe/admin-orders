

import { THEME_DARK_STORAGE } from '@/app/config'
import Api from '@helpers/api'
import Auth from '@helpers/auth'
import routeAllies from '@routes/config'

export default {
  namespaced: true,
  state: {
    ...Auth.default,
    iniAuth: false,
    dark: 1
  },
  getters: {
    isLogin: s => s.token ? (s.token.length > 0 && s.user !== undefined && s.user !== null) : false,
    getToken: s => s.token,
    getUser: s => s.user,
    getUserRole: s => s.user[Auth.role_key] ? s.user[Auth.role_key].toLocaleLowerCase().trim() : '',
    themeDark: s => s.dark
  },
  actions: {
    /**
     * Action login
     * @param commit
     * @param payload
     */
    login ({ commit }, payload) {
      let { token, user } = payload
      token = token ? token : Auth.default.token

      /**
       * Save locale storage
       */
      Auth.save({ token })
      commit('setToken', token)
      commit('setUser', user)
      payload.reload && window && window.location.reload()
    },

    /**
     *
     * Action logout
     * @param commit
     * @param reload
     */
    logout ({ commit }, reload = false) {
      /**
       * remove locale storage
       */
      Auth.logout()
      const { token, user } = Auth.default
      commit('setToken', token)
      commit('setUser', user)
      // reload && window && window.location.reload();
      reload && window && (window.location.href = routeAllies.auth.login)
    },

    /**
     * Action refresh state from localstorage
     * @param commit
     * @param dispatch
     */
    async refresh ({ commit, dispatch }) {

      let { token } = Auth.data() || {}
      token = token || Auth.default.token

      commit('setToken', token)
      commit('setIniAuth', true)

      if (Auth.isLogin()) {
        Api.methods.auth.checkToken().then(({ data }) => {
          if (data && data.data && data.success === true) {
            data = data.data
            commit('setUser', data)
            dispatch('sideMenu/fetchItems', {}, { root: !0 })
          }
        })
      }
    },

    setUser ({ commit }, user) {
      Auth.save(user)
      commit('setUser', user)
    },

    setThemeDark ({ commit }, value) {
      value = value === true ? 1 : 0
      localStorage.setItem(THEME_DARK_STORAGE, value)
      commit('setDark', value)
    },

    async iniAuthFromStorage ({ state, dispatch }) {
      if (state.iniAuth === false) {
        await dispatch('refresh')
      }
      return Auth.isLogin() ? Auth.isLogin() : false
    }
  },
  mutations: {
    setToken: (state, payload) => state.token = payload,
    setUser: (state, payload) => state.user = payload,
    setDark: (state, payload) => state.dark = payload,
    setIniAuth: (state, payload) => state.iniAuth = payload
  }
}
