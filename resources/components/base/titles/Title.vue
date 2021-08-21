
<template>
  <v-toolbar
    v-bind='$attrs'
    v-on='$listeners'
    :class="{'d-inline-flex': flex }"
    :color='color'
    dark
  >
    <v-toolbar-title>
      <slot></slot>
    </v-toolbar-title>
  </v-toolbar>
</template>

<script>
export default {
  name: 'Title',
  props: {
    flex: {
      type: Boolean,
      default: () => !1
    },
    color: {
      type: String,
      default: () => 'accent'
    }
  }
}
</script>
