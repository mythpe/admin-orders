

import { createNamespacedHelpers } from 'vuex'

const { mapGetters, mapActions } = createNamespacedHelpers('auth')
export default {
  methods: {
    setLoginFromResponse ({ token }) {
      // mapActions(['login']).login.call(this, {token, user, reload: true})
      mapActions(['login']).login.call(this, { token, reload: true })
      return this.$auth.data()
    },
    loginUser (token, user) {
      // this.setLoginFromResponse({token, user})
      this.setLoginFromResponse({ token })
      return this.$auth.data()
    },
    logoutUser (reload = false) {
      mapActions(['logout']).logout.call(this, true)
      // reload && window && window.location.reload();
    }
  },
  computed: {
    isLogin: {
      set (n) {
        n === false && this.logoutUser(true)
      },
      get () {
        return mapGetters(['isLogin']).isLogin.call(this)
      }
    }
  }
}
