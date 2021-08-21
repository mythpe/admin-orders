
<template>
  <app-col-span v-bind='$attrs'>
    <p class='ma-0 text-start body-1 font-weight-black'>{{ getLabel }}</p>
    <ValidationProvider
      v-show='getLabel'
      v-slot='v'
      :name='name'
      :rules='getRules'
      :vid='vid'
    >
      <v-text-field
        ref='val'
        v-model='inputValue'
        v-on='$listeners'
        :error-messages='v.errors'
        type='hidden'
      >{{ inputValue }}
      </v-text-field>
      {{ (errors = v.errors) && '' }}
    </ValidationProvider>
    <app-container
      class='pt-0'
      fluid
    >
      <app-row v-if='hasSelectAll'>
        <v-col
          cols='6'
          sm='2'
        >
          <v-checkbox
            v-model='selectAll'
            :disabled='loading'
            :indeterminate='indeterminate'
          >
            <template #label>
              {{ $t('selectAll') }}
            </template>
          </v-checkbox>
        </v-col>
      </app-row>
      <app-row>
        <template v-for='(item,i) in items'>
          <v-col
            md='4'
            sm='6'
          >
            <v-checkbox
              v-model='checkbox'
              v-bind='$attrs'
              :error='hasError'
              :label='item[itemKey]'
              :value='item[itemValue]'
              class='me-5'
              multiple
            />
          </v-col>
        </template>
      </app-row>
    </app-container>
  </app-col-span>
</template>

<script>
export default {
  name: 'CheckboxGroup',
  props: {
    value: {},
    vid: {},
    items: {
      type: Array
    },
    name: {
      // required: !0,
      type: String,
      default: () => ''
    },
    rules: {
      type: [String, Array],
      default: () => undefined
    },
    itemKey: {
      type: String,
      default: () => 'text'
    },
    itemValue: {
      type: String,
      default: () => 'value'
    },
    label: {
      type: String,
      default: () => ''
    },
    required: {
      type: Boolean,
      default: () => false
    }
  },
  data () {
    return {
      errors: [],
      loading: !1
    }
  },
  computed: {
    hasError () {
      return this.errors.length > 0
    },
    inputValue: {
      set (e) {
        this.$emit('input', e)
      },
      get () {
        return this.value
      }
    },
    checkbox: {
      set (e) {
        this.inputValue = e
        this.$refs.val && this.$refs.val.$emit('input', e)
      },
      get () {
        return this.value
      }
    },
    getLabel () {
      const k = this.label ? this.label : this.name
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
    indeterminate () {
      const v = this.value || ''
      return v.length > 0 && v.length !== this.items.length
    },
    selectAll: {
      set (v) {
        if (this.loading) return
        this.loading = !0
        this.checkbox = v ? this.items.map(e => e[this.itemValue]) : []
        this.$nextTick(() => this.loading = !1)
      },
      get () {
        const v = this.value || ''
        return v.length === this.items.length
      }
    },
    hasSelectAll () {
      return this.items && this.items.length > 1
    }
  },
  watch: {
    value: {
      handler (v) {
        this.checkbox = this.value
      },
      immediate: !0
    }
  }
}
</script>
