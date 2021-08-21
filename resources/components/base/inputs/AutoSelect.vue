
<template>
  <app-col-span
    v-bind='$attrs'
    :lazy-model.sync='lazy'
  >
    <ValidationProvider
      v-slot='v'
      :name='name'
      :rules='getRules'
      :vid='vid'
    >
      <slot name='top'></slot>
      <v-autocomplete
        v-bind='$attrs'
        v-on='{...$listeners,on:input}'
        :append-icon='getAppendIcon'
        :clearable='getClearable'
        :error-messages='v.errors'
        :items='items'
        :label='getLabel'
        :loading='loading'
        :multiple='multiple'
        :placeholder='getPlaceholder'
        :readonly='readonly'
        :single-line='singleLine'
        :value='value'
      >
        <template
          v-for='slot in getSlots'
          v-slot:[slot]
        >
          <slot :name='slot'></slot>
        </template>
      </v-autocomplete>
      <slot name='bottom'></slot>
    </ValidationProvider>
  </app-col-span>
</template>

<script>
export default {
  name: 'AutoSelect',
  props: {
    value: {},
    vid: {},
    lazyModel: {
      type: Boolean,
      default: () => false
    },
    items: {
      type: Array
    },
    name: {
      // required: !0,
      type: String,
      default: () => ''
    },
    rules: {
      type: [Array, String],
      default: () => ''
    },
    label: {
      type: String,
      default () {
        return undefined
      }
    },
    placeholder: {
      type: String,
      default () {
        return this.label
      }
    },
    multiple: {
      type: Boolean,
      default: () => undefined
    },
    singleLine: {
      type: Boolean,
      default: () => undefined
    },
    nullText: {
      type: String,
      default: () => undefined
    },
    nullValue: {
      default: () => null
    },
    loading: {
      type: Boolean
    },
    clearable: {
      type: Boolean,
      default: () => true
    },
    required: {
      type: Boolean,
      default: () => false
    },
    readonly: {
      type: Boolean,
      default: () => false
    },
    appendIcon: {
      type: String,
      default: () => '$dropdown'
    }
  },
  methods: {
    input (e) {
      this.$emit('input', e || null)
    }
  },
  computed: {
    getSlots () {
      let s = Object.keys(this.$scopedSlots).filter(i => (['top', 'bottom', 'item'].indexOf(i) < 0))
      // console.log(s);
      return s
    },
    getLabel () {
      const k = this.label !== undefined ? this.label : this.name
      // console.log(k)
      return this.parseAttribute(k)
    },
    getPlaceholder () {
      return this.placeholder !== undefined ? this.parseAttribute(this.placeholder) : undefined
      // return this.placeholder ? this.$t(this.placeholder) : this.$t('replace.choose', {n: this.getLabel});
    },
    getRules () {
      let rules = this.rules || []
      rules = typeof rules === 'string' ? rules.split('|') : rules
      if (this.required === true && rules.indexOf('required') === -1) {
        rules.push('required')
      }
      return rules.join('|')
    },
    lazy: {
      get () {
        return this.lazyModel
      },
      set (v) {
        this.$emit('update:lazyModel', v)
      }
    },
    getClearable () {
      return this.readonly === true ? false : this.clearable
    },
    getAppendIcon () {
      return this.readonly === true ? null : this.appendIcon
    }
  },
  mounted () {
    // console.log(this.$slots)
    // console.log(this.$scopedSlots)
  }
}
</script>
