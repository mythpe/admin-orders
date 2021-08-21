
<template>
  <app-text-input
    v-bind='$attrs'
    v-on='$listeners'
    :counter='false'
    :force-clearable='clearable'
    :name='name'
    :prepend-icon='prependIcon'
    :rules='getRules'
    :value='value'
    readonly
    @click='click'
  >
    <v-dialog
      ref='dialog'
      v-model='modal'
      v-bind='$attrs'
      :max-width='maxWidth'
      :return-value.sync='inputValue'
      :width='width'
      persistent
    >
      <v-date-picker
        v-model='inputValue'
        v-bind='$attrs'
        scrollable
      >
        <app-btn
          color='primary'
          text
          @click='save(inputValue)'
        >
          {{ $t('done') }}
        </app-btn>
        <v-spacer></v-spacer>
        <app-btn
          color='primary'
          text
          @click='cancel'
        >
          {{ $t('cancel') }}
        </app-btn>
      </v-date-picker>
    </v-dialog>
  </app-text-input>
</template>

<script>

import TextInput from './TextInput'

export default {
  name: 'DatePicker',
  extends: TextInput,
  props: {
    maxWidth: {
      type: [String, Number],
      default: () => 290
    },
    width: {
      type: [String, Number],
      default: () => 290
    },
    prependIcon: {
      type: String,
      default: () => 'event'
    },
    clearable: {
      type: Boolean,
      default: () => !0
    },
    readonly: {
      type: Boolean,
      default: () => false
    }
  },
  data () {
    return {
      inputValue: undefined,
      modal: false
    }
  },
  methods: {
    input ($event) {
      this.$emit('input', $event)
    },
    save (v) {
      this.$refs.dialog.save(v)
      this.input(v)
    },
    click () {
      if (this.readonly === true) return
      this.modal = !this.modal
    },
    cancel () {
      this.modal = !1
    }
  },
  mounted () {
    this.inputValue = this.value
  }
}
</script>
