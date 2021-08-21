

import Vue from '@plugins/vue'
import _ from 'lodash'

_.mixin(require('lodash-inflection'))

Vue.prototype.$_ = _
window._ = _

export default _
