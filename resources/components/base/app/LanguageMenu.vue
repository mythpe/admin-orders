
<template>
  <v-menu
    :left='!AppRtl'
    offset-y
    tile
  >
    <template v-slot:activator='{ on, attrs }'>
      <v-btn
        v-bind='attrs'
        v-on='on'
        icon
      >
        <v-icon>mdi-earth</v-icon>
      </v-btn>
    </template>
    <v-list
      dense
      tile
    >
      <v-list-item-group v-model='AppLocale'>
        <template v-for='(item, i) in AppLocales'>
          <v-list-item
            :disabled='item.code === AppLocale'
            :value='item.code'
            @click='SetAppLocale(item.code)'
          >
            <v-list-item-title class='text-center'>{{ item.title }}</v-list-item-title>
          </v-list-item>
        </template>
      </v-list-item-group>
    </v-list>
  </v-menu>
</template>

<script>
export default {
  name: 'LanguageMenu'
}
</script>
