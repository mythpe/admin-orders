
<template>
  <v-container fluid>
    <app-row
      v-if='getTitle'
      no-gutters
    >
      <app-col all='12'>
        <app-page-title>
          <slot name='top-title'></slot>
          {{ getTitle }}
          <slot name='bottom-title'></slot>
        </app-page-title>
      </app-col>
    </app-row>
    <app-row>
      <app-col all='12'>
        <slot name='top'></slot>
        <slot></slot>
        <slot name='bottom'></slot>
      </app-col>
    </app-row>
  </v-container>
</template>

<script>
export default {
  name: 'Page',
  props: {
    title: {
      type: String,
      default: () => null
    }
  },
  computed: {
    getTitle () {
      return this.title || this.$parent && this.$parent.getPageTitle() || ''
    }
  }
}
</script>

<style scoped>

</style>
