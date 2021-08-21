
<template>
  <app-col-span v-bind='$attrs'>
    <ValidationProvider
      ref='provider'
      v-slot='v'
      :mode='mode'
      :name='name'
      :rules='getRules'
      :vid='vid'
    >
      <slot name='top'></slot>
      <v-text-field
        v-bind='$attrs'
        v-on='{...$listeners,on:input}'
        :clearable='getClearable'
        :counter='counter'
        :counter-value='a => a ? a.length : 0'
        :error='getErrorMessages.length>0'
        :error-count='getErrorCount'
        :error-messages='[...getErrorMessages,...v.errors]'
        :label='getLabel'
        :placeholder='getPlaceholder'
        :readonly='readonly'
        :value='value'
      />
      <slot></slot>
      <slot name='bottom'></slot>
    </ValidationProvider>
  </app-col-span>
</template>

<script>
export default {
  name: 'TextInput',
  props: {
    name: {
      required: true
    },
    value: {},
    vid: {},
    mode: {},
    rules: {
      type: [Array, String],
      default: () => ''
    },
    label: {},
    errors: {
      type: Array,
      default: () => []
    },
    errorCount: {
      type: [Number, String],
      default: () => undefined
    },
    placeholder: {
      default () {
        return this.label
      }
    },
    clearable: {
      type: Boolean,
      default: () => true
    },
    readonly: {
      type: Boolean,
      default: () => false
    },
    counter: {
      type: Boolean,
      default: () => true
    },
    required: {
      type: Boolean,
      default: () => false
    },
    forceClearable: {
      type: Boolean,
      default: () => false
    }
  },
  computed: {
    getErrorCount () {
      return this.errorCount === undefined ? 1 : (parseInt(this.errorCount) || 1)
    },
    getErrorMessages () {
      const provider = this.$refs.provider
      let errors = [...this.errors]
      provider && (errors = [...errors, ...provider.errors])

      return errors
    },
    getLabel () {
      let k = this.label !== undefined ? this.label : this.name
      return this.parseAttribute(k)
    },
    getPlaceholder () {
      let k = this.placeholder !== undefined ? this.placeholder : this.name
      return this.parseAttribute(k)
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
      return this.forceClearable === true ? true : (this.readonly === true ? false : this.clearable)
    }
  },
  methods: {
    input (v) {
      this.$emit('input', v)
    }
  }
}
</script>

<style scoped>

</style>
