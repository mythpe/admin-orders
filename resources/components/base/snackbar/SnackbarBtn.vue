
<template>
  <v-btn
    v-bind='$attrs'
    v-on='$listeners'
    text
    tile
  >
    <slot></slot>
  </v-btn>
</template>

<script>

export default {
  name: 'SnackbarBtn'
}
</script>
