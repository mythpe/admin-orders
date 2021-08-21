

import Vue from 'vue'
import Vuex from 'vuex'
import modules from './register'

Vue.use(Vuex)

const
  state = {},
  getters = {},
  actions = {},
  mutations = {}

const options = {
  modules,
  state,
  getters,
  actions,
  mutations
}
// console.log(modules)
export default new Vuex.Store(options)

