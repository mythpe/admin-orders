

import Auth from '@helpers/auth'
import Vue from '@plugins/vue'
import { HOME_ROUTE_NAME, LOGIN_ROUTE_NAME, USER_HOME_ROUTE_NAME } from '@routes/config'
import VueRouter from 'vue-router'
import goTo from 'vuetify/es5/services/goto'
import routes from './register'

Vue.use(VueRouter)
// console.log(routes)
const options = {
  mode: 'history',
  routes,
  scrollBehavior: (to, from, savedPosition) => {
    const scrollTo = 0,
      options = {
        duration: 700,
        offset: 50
      }
    return goTo(to.hash ? to.hash : (savedPosition ? savedPosition.y : scrollTo), options)
  }
}

const router = new VueRouter(options)

router.beforeResolve((to, from, next) => {
  // router.app.$root.startProgress()
  next()
})

router.afterEach((to, from) => {
  setTimeout(() => router.app.$root.stopProgress(), 250)
  // router.app.setAxiosCountdown(200)
})

router.beforeEach((to, from, next) => {

  let name
  if (to.matched.some(r => r.meta.auth)) {
    if (!Auth.isLogin()) {
      name = LOGIN_ROUTE_NAME
      next({ name })
    } else {
      next()
    }
  } else if (to.matched.some(r => r.meta.guest)) {
    if (Auth.isLogin() && from && from.name === HOME_ROUTE_NAME) {
      next()
      return
    }

    if (Auth.isLogin()) {
      name = USER_HOME_ROUTE_NAME
      next({ name })
    } else {
      next()
    }
  } else {
    next()
  }

})

export default router
