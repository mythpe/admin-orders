
<template>
  <v-progress-linear
    v-bind='$attrs'
    v-on='$listeners'
    :active='$root.AppProgress'
    class='mb-1'
    fixed
    indeterminate
  ></v-progress-linear>
</template>

<script>
export default {
  name: 'AppProgressLinear'
}
</script>

<style scoped>

</style>
