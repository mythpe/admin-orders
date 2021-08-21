
import { Tools } from '@helpers/tools'
import Vue from 'vue'

Vue.directive('numeric', {
  bind: function (el, binding, { context }) {
    // console.log(binding.value);
    if (binding.value === false) return

    const numeric = e => {
      const k = Tools.fromArabicNumber(e.key || '') || ''
      // let escape = ['.'];
      let escape = []
      if (binding.value && Tools.isArray(binding.value)) {
        escape = [...escape, ...binding.value]
      }
      // console.log(e.target);
      const val = e.target.value

      // if(!Tools.isNumeric(k) && k === '.' && escape.indexOf(k) >= 0 && val && val.indexOf('.') >=0){
      if (!Tools.isNumeric(k) && escape.indexOf(k) >= 0 && val && val.indexOf(k) >= 0) {
        e.preventDefault()
      }

      if ((!Tools.isNumeric(k) && escape.indexOf(k) < 0) || !k.trim()) {
        e.preventDefault()
      }
    }

    const focus = e => {
      e.target.dataset.focusNumeric = '1'
      e.target.focus()
      context.$nextTick(() => e.target.select())
    }

    const blur = e => {
      e.target.dataset.focusNumeric = '0'
    }

    let e
    el.addEventListener('keypress', numeric)

    if (el.nodeName.toLowerCase() === 'input') {
      el.addEventListener('focus', focus)
      el.addEventListener('blur', blur)
    } else if ((e = el.querySelector('input'))) {
      e.addEventListener('focus', focus)
      e.addEventListener('blur', blur)
    }
  }
})
