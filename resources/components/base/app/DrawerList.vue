
<template>
  <v-list dense>
    <template v-for='(item, i) in items'>
      <app-list-item :item='item'></app-list-item>
    </template>
  </v-list>
</template>

<script>

export default {
  name: 'DrawerList',
  props: ['items']
}
</script>
