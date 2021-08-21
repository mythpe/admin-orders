
<template>
  <v-btn
    v-bind='$attrs'
    v-on='$listeners'
    fab
    icon
    tile
    x-small
  >
    <slot></slot>
  </v-btn>
</template>

<script>
export default {
  name: 'SystemBarBtn'
}
</script>

<style scoped>

</style>
