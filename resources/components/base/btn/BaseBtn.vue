
<template>
  <v-btn
    v-bind='$attrs'
    v-on='$listeners'
    :class='getClasses'
    :color='color'
    :min-width='getMinWidth'
    :outlined='outlined'
    :retain-focus-on-click='retainFocusOnClick'
    :tile='tile'
  >
    <slot></slot>
  </v-btn>
</template>

<script>
export default {
  name: 'BaseBtn',
  props: {
    color: {
      type: String,
      default: () => 'primary'
    },
    minWidth: {
      type: [String, Number],
      default: () => undefined
    },
    tile: {
      type: Boolean,
      default: () => !1
    },
    outlined: {
      type: Boolean,
      default: () => !1
    },
    retainFocusOnClick: {
      type: Boolean,
      default: () => !1
    }
  },
  computed: {
    getClasses: () => [
      'app-btn',
      'my-1'
    ].join(' '),
    getMinWidth () {
      return this.$attrs.icon === undefined ? this.minWidth : undefined
    }
  }
}
</script>
