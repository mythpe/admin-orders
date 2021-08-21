
<template>
  <v-icon
    v-bind='$attrs'
    v-on='$listeners'
    :left='getLeft'
    :right='getRight'
  >
    <slot></slot>
  </v-icon>
</template>

<script>
export default {
  name: 'Icon',
  props: {
    left: {
      type: Boolean,
      default () {
        return !Boolean(this.AppRtl)
      }
    },
    right: {
      type: Boolean,
      default () {
        return Boolean(this.AppRtl)
      }
    },
    align: {
      type: Boolean,
      default: () => undefined
    }
  },
  computed: {
    getLeft () {
      return this.align !== undefined ? !this.AppRtl : this.left
    },
    getRight () {
      return this.align !== undefined ? this.AppRtl : this.right
    }
  },
  mounted () {
    // console.log(this.right, this.left)
  }
}
</script>
