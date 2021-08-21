
<template>
  <app-col-span v-bind='$attrs'>
    <slot name='top'></slot>
    <v-text-field
      ref='string'
      v-model='val'
      v-bind='$attrs'
      v-numeric='vNumericEscapes'
      v-on='inputListeners'
      :autocomplete='autocomplete'
      :clearable='getClearable'
      :counter='counter'
      :counter-value='a => a ? a.length : 0'
      :error-messages='errorMessages'
      :label='getLabel'
      :placeholder='getPlaceholder'
      type='tel'
      v-on:input='input'
    />
    <ValidationProvider
      ref='provider'
      v-slot='v'
      :immediate='immediate'
      :name='name'
      :rules='getRules'
      :vid='vid'
      mode='aggressive'
    >
      <v-text-field
        v-show='!1'
        ref='number'
        v-model='inputValue'
      />
      {{ (providerMessages = v.errors) && '' }}
    </ValidationProvider>
    <slot></slot>
    <slot name='bottom'></slot>
  </app-col-span>
</template>

<script>
export default {
  name: 'NumberInput',
  props: {
    value: {},
    vid: {},
    minus: {
      type: Boolean,
      default: () => false
    },
    counter: {
      type: Boolean,
      default: () => true
    },
    clearable: {
      type: Boolean,
      default: () => true
    },
    autocomplete: {
      type: String,
      default: () => 'off'
    },
    escapeInput: {
      default: () => []
    },
    immediate: {
      default: () => false
    },
    name: {
      type: String,
      required: true
    },
    rules: {
      type: [String, Array],
      default () {
        return ''
      }
    },
    label: {
      default: () => undefined
    },
    placeholder: {
      default () {
        return this.label
      }
    },
    format: {
      type: Boolean,
      default: () => false
    },
    errors: {
      type: Array,
      default: () => ([])
    },
    required: {
      type: Boolean,
      default: () => false
    },
    readonly: {
      type: Boolean,
      default: () => false
    }
  },
  watch: {
    value (n) {
      this.val = n
    }
  },
  mounted () {
    this.iniComponent()
  },
  data () {
    return {
      inputValue: null,
      string: null,
      providerMessages: []
    }
  },
  methods: {
    iniComponent () {
      this.val = this.value
    },
    toNumber (v) {
      return this.$helpers.toNumber(v)
    },
    toNumberFormat (v) {
      if (!v) return v
      return this.format === true ? this.$helpers.toNumberFormat(this.$helpers.toNumber(v)) : '' + v
    },
    input (value) {
      value = this.toNumber(value)
      this.$refs.number.$emit('input', value)
      this.$emit('input', value)
      // console.log(value)

    },
    focus ($event) {
      this.$refs.number.$emit('focus', $event)
      this.$emit('focus', $event)
    },
    change ($event) {
      this.$refs.number.$emit('change', $event)
      this.$emit('change', $event)
    },
    blur ($event) {
      this.$refs.number.$emit('blur', $event)
      this.$emit('blur', $event)
    },
    keypress ($event) {
      this.$refs.number.$emit('keypress', $event)
      this.$emit('keypress', $event)
    },
    keydown ($event) {
      this.$refs.number.$emit('keypress', $event)
      this.$emit('keydown', $event)
    },
    setVal (value) {
      this.string = this.toNumberFormat(value)
      this.inputValue = this.toNumber(value)
    }
  },
  computed: {
    errorMessages () {
      return [...this.errors, ...this.providerMessages]
      // const provider = this.$refs.provider;
      // provider && (errors = [...errors, ...provider.errors]);
      // console.log(provider);
      // return errors;
    },
    getLabel () {
      let k = this.label !== undefined ? this.label : this.name
      return this.parseAttribute(k)
    },
    getPlaceholder () {
      let k = this.placeholder !== undefined ? this.placeholder : this.name
      return this.parseAttribute(k)
    },
    inputListeners () {
      const vm = this
      return Object.assign({}, this.$listeners,
        {
          input: val => vm.input(val),
          focus: val => vm.focus(val),
          change: val => vm.change(val),
          blur: val => vm.blur(val),
          keypress: val => vm.blur(val),
          keydown: val => vm.blur(val)
        })
    },
    val: {
      set (n) {
        this.setVal(n)
      },
      get () {
        return this.string
      }
    },
    vNumericEscapes () {
      let e = this.escapeInput

      if (this.minus && this.$helpers.isOnlyArray(e) && e.indexOf('-') < 0) {
        e.push('-')
      }

      if (this.format && this.$helpers.isOnlyArray(e) && e.indexOf('.') < 0) {
        e.push('.')
      }

      return e
    },
    getRules () {
      let rules = this.rules || []
      rules = typeof rules === 'string' ? rules.split('|') : rules
      if (this.required === true && rules.indexOf('required') === -1) {
        rules.push('required')
      }
      return rules.join('|')
    },
    getClearable () {
      return this.readonly === true ? false : this.clearable
    }
  }
}
</script>
