
import Vue from 'vue'
import { configure, extend, localize, setInteractionMode, ValidationObserver, ValidationProvider } from 'vee-validate/dist/vee-validate.full.esm'

require('./rules')

configure({
  classes: {
    valid: 'is-valid',
    invalid: 'is-invalid'
  },
  bails: false,
  immediate: true,
  mode: 'eager'
  // mode: "aggressive",
  // mode: "passive",
  // defaultMessage: (field, values) => {
  //     const a = `validations.names.${field}`, b =i18n.t(a);
  //     values._field_ = b === a ? '' : b;
  //     return i18n.t(`validations.messages.${values._rule_}`, values);
  // }
})
// Eager
Vue.component('ValidationProvider', ValidationProvider)
Vue.component('ValidationObserver', ValidationObserver)
export {
  ValidationProvider,
  ValidationObserver,
  localize,
  configure,
  extend,
  setInteractionMode
}
