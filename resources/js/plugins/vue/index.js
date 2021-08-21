

import APP_CONFIG from '@/app/config'
import Api from '@helpers/api'
import Auth from '@helpers/auth'

import { Tools } from '@helpers/tools'
import AppMixins from '@mixins/AppMixins'
import AuthMixins from '@mixins/AuthMixins'
import GlobalMixins from '@mixins/GlobalMixins'
import SnackbarMixins from '@mixins/SnackbarMixins'
import Vue from 'vue'

window.Vue = Vue

Vue.mixin(AppMixins)
Vue.mixin(GlobalMixins)
Vue.mixin(AuthMixins)
Vue.mixin(SnackbarMixins)

Vue.prototype.$api = Api
Vue.prototype.$auth = Auth
Vue.prototype.$config = APP_CONFIG
Vue.prototype.$helpers = { ...Tools }
Vue.prototype.goTo = Tools.goTo

require('./filters')
require('./components')

const requireComponent = require.context('./directives', true, /\.js$/)
requireComponent.keys().forEach((f) => requireComponent(f))
export default Vue
