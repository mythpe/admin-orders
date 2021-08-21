
<template>
  <v-tooltip
    v-bind='getAttrs'
    v-on='$listeners'
  >
    <template v-slot:activator='{ on, attrs }'>
      <div
        v-bind='attrs'
        v-on='on'
        :class='activatorClass'
      >

        <slot></slot>
      </div>
    </template>
    <span>{{ getText }}</span>
  </v-tooltip>
</template>

<script>

export default {
  name: 'Tooltip',
  props: {
    text: {
      type: String,
      default: () => ''
    }
  },
  computed: {
    activatorClass () {
      return [
        'd-inline-flex'
      ].join(' ')
    },
    getText () {
      return this.text
    },
    getAttrs () {
      return { top: true, ...this.$attrs }
    }
  }
}
</script>
