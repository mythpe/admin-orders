
<template>
  <v-row
    v-bind='$attrs'
    v-on='$listeners'
    :class='getClasses'
  >
    <slot></slot>
  </v-row>
</template>

<script>
export default {
  name: 'Row',
  computed: {
    getClasses: () => [
      'app-row'
    ].join(' ')
  }
}
</script>
