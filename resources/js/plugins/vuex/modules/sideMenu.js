

import Api from '@helpers/api'

export default {
  namespaced: true,
  state: {
    items: [],
    fetching: !0
  },
  getters: {
    getItems: s => s.items,
    getFetching: s => s.fetching
  },
  actions: {
    fetchItems ({ commit }) {
      Api.methods.user.sideMenu().then(({ data }) => {
        if (data && data.data) {
          commit('setItems', data.data)
        }
      }).finally(() => commit('setFetching', !1))
    }
  },
  mutations: {
    setItems: (s, payload) => s.items = payload,
    setFetching: (s, payload) => s.fetching = payload
  }
}
