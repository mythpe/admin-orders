
<template>
  <div class='mb-4'>
    <v-toolbar
      color='light-blue darken-4'
      dark
      dense
      elevation='10'
      flat
    >
      <v-toolbar-title>
        <slot></slot>
      </v-toolbar-title>
    </v-toolbar>
    <v-divider class='mt-3 mb-2'></v-divider>
  </div>
</template>

<script>
export default {
  name: 'PageTitle'
}
</script>

<style scoped>

</style>
