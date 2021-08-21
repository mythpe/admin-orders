
import ProgressMixin from '@mixins/ProgressMixin'
import RootMixin from '@mixins/RootMixin'
import Vue from '@plugins/vue'
import i18n from '@plugins/vue-i18n'
import router from '@plugins/vue-router'
import vuetify from '@plugins/vuetify'
import store from '@plugins/vuex'
import AppRouter from '@views/AppRouter'

require('./bootstrap')

Vue.config.productionTip = process.env.NODE_ENV === 'development'
Vue.config.devtools = process.env.NODE_ENV === 'development'

const app = new Vue({
  el: '#app',
  vuetify,
  router,
  i18n,
  store,
  mixins: [ProgressMixin, RootMixin],
  render: h => h(AppRouter),
})

export default app
