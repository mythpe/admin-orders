
<template>
  <app-text-input
    v-bind='$attrs'
    v-on='$listeners'
    :clearable='clearable'
    :counter='false'
    :name='name'
    :prepend-icon='prependIcon'
    :rules='rules'
    :value='value'
    :vid='vid'
    readonly
    @click='click'
    v-on:input='click'
  >
    <v-dialog
      ref='dialog'
      v-model='modal'
      v-bind='$attrs'
      :max-width='maxWidth'
      :return-value.sync='date'
      :width='width'
      persistent
    >
      <v-time-picker
        v-model='date'
        :max='max'
        :min='min'
        ampm-in-title
        scrollable
      >
        <app-btn
          color='primary'
          text
          @click='save(date)'
        >
          {{ $t('done') }}
        </app-btn>
        <v-spacer></v-spacer>
        <app-btn
          color='error'
          text
          @click='cancel'
        >
          {{ $t('cancel') }}
        </app-btn>
      </v-time-picker>
    </v-dialog>
  </app-text-input>
</template>

<script>

import TextInput from './TextInput'

export default {
  name: 'TimePicker',
  extends: TextInput,
  props: {
    vid: {},
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
      default: () => 'access_time'
    },
    clearable: {
      type: Boolean,
      default: () => !0
    },
    min: {
      default: () => undefined
    },
    max: {
      default: () => undefined
    }
  },
  data () {
    return {
      modal: false
    }
  },
  computed: {
    date: {
      get () {
        return this.value
      },
      set (v) {
        this.emit(v)
      }
    }
  },
  methods: {
    input ($event) {
      this.$emit('input', $event)
    },
    save (v) {
      this.$refs.dialog.save(v)
    },
    click () {
      this.modal = !this.modal
    },
    cancel () {
      this.modal = !1
    },
    emit (v) {
      this.$emit('input', v)
    }
  }
}
</script>
