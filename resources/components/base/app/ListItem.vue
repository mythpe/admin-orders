
<template>
  <div>
    <template v-if='item.items'>
      <v-list-group
        :active-class='activeClasses'
        :prepend-icon='!subGroup ? item.icon : null'
        :sub-group='subGroup'
        :value='getActiveList'
      >
        <template v-slot:activator>
          <template v-if='subGroup'>
            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </template>
          <template v-else>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </template>
        </template>
        <template v-for='(sub, i) in item.items'>
          <app-list-item
            :key='i'
            :item='sub'
          ></app-list-item>
        </template>
      </v-list-group>
    </template>
    <template v-else>
      <v-list-item
        :active-class='activeClasses'
        :exact-active-class='activeClasses'
        :to='route(item.name)'
        link
      >
        <v-list-item-icon v-if='item.icon'>
          <v-icon>{{ item.icon }}</v-icon>
        </v-list-item-icon>
        <v-list-item-title>{{ item.title }}</v-list-item-title>
      </v-list-item>
    </template>
  </div>
</template>

<script>

export default {
  name: 'ListItem',
  props: {
    item: {
      required: !0
    },
    subGroup: {
      default: () => !0
    }
  },
  data () {
    return {
      // activeClasses: [
      //   'active-item',
      //   'secondary--text',
      //   this.themeLight ? 'darken-3' : 'lighten-3'
      // ].join(' ')
    }
  },
  computed: {
    getActiveList () {
      const { items } = this.item || []
      // return
      // console.log(Boolean(this.$helpers.findBy(items, this.$route.name, 'name')))
      return this.$helpers.findBy(items, this.$route.name, 'name')
    },
    activeClasses () {
      return [
        'active-item',
        'secondary--text',
        this.themeLight ? 'darken-3' : 'lighten-3'
      ].join(' ')
    }
  }
}
</script>
<style
  lang='scss'
  scoped
>
.v-list-item.active-item {
  color: var(--v-secondary-base) !important;
}

</style>
