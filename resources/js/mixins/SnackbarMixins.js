

import _ from 'lodash'
import { createNamespacedHelpers } from 'vuex'

const { mapActions } = createNamespacedHelpers('snackbar')

export default {
  methods: {
    alert (text = '', type = '', callback = null) {
      if (_.isObject(text)) {
        type = text.type || null
        callback = text.callback || null
        text = text.text
      }

      if (type && typeof type !== 'string') {
        callback = type
        type = null
      }
      return mapActions(['showSnackbar']).showSnackbar.call(this, { text, type, callback })
    },
    alertError (text = '', callback = null) {
      return this.alert(text, 'error', callback)
    },
    alertSuccess (text = '', callback = null) {
      return this.alert(text, 'success', callback)
    },
    toast (text = '', type = 'normal', toast = !0) {
      return mapActions(['showSnackbar']).showSnackbar.call(this, { text, type, toast })
    },
    confirm (text = '', callback = null, reject = null, type = null) {
      let confirm = !0
      if (typeof text === 'function') {
        callback = text
        text = this.$t('messages.areYouSure')
      }
      return mapActions(['showSnackbar']).showSnackbar.call(this, { text, type, callback, confirm, reject })
    }
  }
}
