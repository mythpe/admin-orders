
<template>
  <v-col
    v-bind='$attrs'
    v-on='$listeners'
    :class='getClasses'
    :cols='cols'
    :lg='lg'
    :md='md'
    :sm='sm'
  >
    <slot></slot>
  </v-col>
</template>

<script>
export default {
  name: 'Col',
  props: {
    all: {
      type: [String, Number],
      default: () => undefined
    },
    cols: {
      type: [Number, String],
      // default: () => undefined
      default () {
        return this.all !== undefined ? this.all : 12
      }
    },
    sm: {
      type: [Number, String],
      default () {
        // return this.all !==undefined ? this.all : undefined
        return this.all !== undefined ? this.all : (this.cols || 6)
      }
    },
    md: {
      type: [Number, String],
      default () {
        return this.all !== undefined ? this.all : (this.sm || this.cols || 4)
      }
    },
    lg: {
      type: [Number, String],
      default () {
        return this.all !== undefined ? this.all : (this.md || this.sm || this.cols || 4)
      }
    },
    left: {
      type: Boolean,
      default: () => true
    },
    right: {
      type: Boolean,
      default: () => false
    }
  },
  computed: {
    align () {
      return (this.right && !this.left ? 'right' : (this.left && !this.right ? 'left' : 'center'))
    },
    getClasses () {
      return [
        'app--col',
        'text-center',
        `text-sm-start`
      ].join(' ')
    }
  }
}
</script>
