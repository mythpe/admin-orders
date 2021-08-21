
<template>
  <v-sheet
    v-if='loading'
    v-bind='$attrs'
    :class='getClasses'
    :color='color'
  >
    <v-responsive
      class='mx-auto'
      max-width='100%'
    >
      <template v-for='i in count'>
        <v-skeleton-loader
          :type='type'
          class='mx-auto mb-1'
        ></v-skeleton-loader>
      </template>
    </v-responsive>
  </v-sheet>
</template>

<script>
export default {
  name: 'Skeleton',
  props: {
    loading: {
      type: Boolean,
      required: !0
    },
    count: {
      type: [String, Number],
      default: () => 1
    },
    color: {
      type: String,
      default: () => 'transparent'
    },
    type: {
      type: String,
      default: () => 'list-item-avatar'
    }
  },
  computed: {
    getClasses: () => [
      'pa-0'
      // 'pt-5',
    ].join(' ')
  }
}
</script>
