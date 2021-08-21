

const DEF_TYPE = 'normal'
export default {
  namespaced: !0,
  state: {
    show: !1,
    text: null,
    type: DEF_TYPE,
    types: {
      success: {
        btnColor: '#fff',
        iconColor: '#fff',
        snackbar: 'success',
        // icon: 'mdi-check-bold',
        icon: 'mdi-check-outline'
      },
      error: {
        btnColor: '#fff',
        iconColor: '#fff',
        snackbar: 'error',
        icon: 'mdi-alert-circle'
      },
      warning: {
        btnColor: '#fff',
        iconColor: '#fff',
        snackbar: 'warning',
        icon: 'mdi-alert-circle'
      },
      info: {
        btnColor: '#fff',
        iconColor: '#fff',
        snackbar: 'info',
        icon: 'mdi-information-variant'
      },
      normal: {
        btnColor: 'black',
        iconColor: '',
        snackbar: '#fff',
        icon: ''
      }
    },
    callback: null,
    reject: null,
    confirm: !1,
    toast: !1
  },
  getters: {
    getShow: state => state.show,
    getText: state => state.text,
    getType: state => state.type,
    getTypes: state => state.types,
    getCallback: state => state.callback,
    getConfirm: state => state.confirm,
    getReject: state => state.reject,
    getToast: state => state.toast
  },
  actions: {
    showSnackbar ({ commit, state }, { text = '', type = DEF_TYPE, callback = null, confirm = !1, reject = null, toast = !1 }) {
      commit('setShow', !0)
      commit('setText', text)
      commit('setType', type)
      commit('setCallback', callback)
      commit('setConfirm', confirm)
      commit('setReject', reject)
      commit('setToast', toast)
    },
    hideSnackbar ({ commit }) {
      commit('setShow', !1)
      commit('setText', null)
      commit('setType', DEF_TYPE)
      commit('setCallback', null)
      commit('setConfirm', !1)
      commit('setReject', null)
      commit('setToast', !1)
    }
  },
  mutations: {
    setShow: (state, payload = !0) => state.show = payload,
    setText: (state, payload = '') => state.text = payload,
    setType: (state, payload = DEF_TYPE) => state.type = state.types[payload] ? payload : DEF_TYPE,
    setCallback: (state, payload = null) => state.callback = payload,
    setConfirm: (state, payload = !1) => state.confirm = payload,
    setReject: (state, payload = !1) => state.reject = payload,
    setToast: (state, payload = !1) => state.toast = payload
  }
}
