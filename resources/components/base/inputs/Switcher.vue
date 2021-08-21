
<template>
  <app-col-span v-bind='$attrs'>
    <ValidationProvider
      v-slot='v'
      :name='name'
      :rules='rules'
      :vid='vid'
    >
      <slot name='top'></slot>
      <v-switch
        v-model='val'
        v-bind='$attrs'
        v-on='$listeners'
        :error-count='v.errors.length'
        :error-messages='v.errors'
        :label='getLabel'
        :value='switchValue'
        class='mx-2'
      />
      <slot></slot>
      <slot name='bottom'></slot>
    </ValidationProvider>
  </app-col-span>
</template>

<script>
export default {
  name: 'Switcher',
  props: {
    value: {},
    vid: {},
    name: {
      required: true
    },
    switchValue: {
      default: () => undefined
    },
    rules: {
      default: () => ''
    },
    label: {
      type: String,
      // default: () => 'is_active'
      default: () => undefined
    },
    statusNames: {
      type: Array,
      default: () => (['active', 'status'])
    },
    defaultValue: {
      default: () => undefined
    }
  },
  computed: {
    getLabel () {
      return this.parseAttribute(this.label || this.name)
    },
    val: {
      get () {
        return (this.value === undefined ? this.defaultValue : this.value)
        // return (this.value === undefined ? (this.statusNames.indexOf(this.name) === -1 ? this.defaultValue : !0) : this.value);
      },
      set (v) {
        // console.log(2);
        this.$emit('input', v)
      }
    }
  }
}
</script>
