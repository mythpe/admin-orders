
<template>
  <component
    :is='getComponentName'
    v-bind='$attrs'
  >
    <!--      <v-lazy-->
    <!--          v-model="lazy"-->
    <!--          :options="{ threshold: 1}"-->
    <!--          transition="fade-transition"-->
    <!--      >-->
    <!--    <span>-->
    <slot></slot>
    <!--    </span>-->
    <!--      </v-lazy>-->
  </component>
</template>

<script>
export default {
  name: 'ColSpan',
  props: {
    lazyModel: {
      type: Boolean,
      default: () => false
    },
    withCol: {
      type: Boolean,
      default: () => true
    }
  },
  data () {
    return {
      // lazy: this.lazyModel
    }
  },
  computed: {
    getComponentName () {
      return this.withCol ? 'app-col' : 'span'
    },
    lazy: {
      get () {
        return this.lazyModel
      },
      set (v) {
        // console.log('update:lazyModel', v);
        this.$emit('update:lazyModel', v)
      }
    }
  },
  mounted () {
    // this.lazy = this.lazyModel;
  }
}
</script>
