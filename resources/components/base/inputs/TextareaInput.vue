
<template>
  <app-col-span v-bind='$attrs'>
    <ValidationProvider
      v-slot='v'
      :name='name'
      :rules='rules'
      :vid='vid'
    >
      <slot name='top'></slot>
      <v-textarea
        v-bind='$attrs'
        v-on='$listeners'
        :auto-grow='autoGrow'
        :counter-value='a => a ? a.length : 0'
        :error-messages='v.errors'
        :label='getLabel'
        :placeholder='getPlaceholder'
        :value='value'
        clearable
        counter
        v-on:input="$emit('input',$event)"
      />
      <slot></slot>
      <slot name='bottom'></slot>
    </ValidationProvider>
  </app-col-span>
</template>

<script>
export default {
  name: 'TextareaInput',
  props: {
    vid: {},
    value: {},
    name: {
      required: true
    },
    rules: {
      default: () => ''
    },
    label: {},
    placeholder: {
      default () {
        return this.label
      }
    },
    autoGrow: {
      default: () => true
    }
  },
  computed: {
    getLabel () {
      let k = this.label !== undefined ? this.label : this.name
      return this.parseAttribute(k)
    },
    getPlaceholder () {
      let k = this.placeholder !== undefined ? this.placeholder : this.name
      return this.parseAttribute(k)
    }
  }
}
</script>
