
<template>
  <v-container
    v-bind='$attrs'
    v-on='$listeners'
  >
    <slot></slot>
  </v-container>
</template>

<script>
export default {
  name: 'Container'
}
</script>
