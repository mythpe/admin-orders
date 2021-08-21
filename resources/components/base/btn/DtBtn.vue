
<template>
  <app-tooltip
    :disabled='tooltipDisabled'
    :text='getTooltip'
  >
    <v-btn
      v-bind='$attrs'
      v-on='$listeners'
      :class='getClasses'
      :min-width='minWidth'
      fab
      small
    >
      <template v-if='update'>
        <v-icon color='success'>edit</v-icon>
      </template>
      <template v-if='show'>
        <v-icon color='primary'>visibility</v-icon>
      </template>
      <template v-if='destroy'>
        <v-icon color='error'>delete</v-icon>
      </template>
      <slot></slot>
    </v-btn>
  </app-tooltip>
</template>

<script>
export default {
  name: 'Btn',
  props: {
    minWidth: {
      type: [String, Number],
      default: () => undefined
    },
    update: {
      type: Boolean,
      default: () => false
    },
    show: {
      type: Boolean,
      default: () => false
    },
    destroy: {
      type: Boolean,
      default: () => false
    },
    tooltip: {
      default: () => undefined
    }
  },
  computed: {
    getClasses: () => [
      'app-dt-btn',
      'my-2'
    ].join(' '),
    getMinWidth () {
      return this.$attrs.icon === undefined ? this.minWidth : undefined
    },
    getTooltip () {
      if (this.tooltip) return this.parseAttribute(this.tooltip)
      if (this.update) return this.$t('update')
      if (this.show) return this.$t('show')
      if (this.destroy) return this.$t('destroy')
      return null
    },
    tooltipDisabled () {
      return this.getTooltip === null || this.getTooltip === undefined
    }
  }
}
</script>
