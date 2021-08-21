
<template>
  <ValidationObserver
    :ref='name'
    v-slot='v'
    v-bind='$attrs'
    v-on='$listeners'
  >
    <v-form @submit.prevent='v.handleSubmit(() => submit(v))'>
      <slot v-bind='v'></slot>
    </v-form>
  </ValidationObserver>
</template>

<script>
export default {
  name: 'Form',
  props: {
    submit: {
      type: Function,
      default: () => () => null
      // required: true
    },
    name: {
      type: String,
      default: () => 'form'
    },
    errors: {
      type: Object,
      default: () => ({})
    }
  },
  watch: {
    errors: {
      deep: !0,
      immediate: !0,
      handler (errors, old) {
        // console.log(errors)
        this.setErrors(errors)
      }
    }
  },
  computed: {
    // form() {
    //   return this.$refs[this.name];
    // }
  },
  methods: {
    reset (...args) {
      this.$refs[this.name] && this.$refs[this.name].reset(...args)
    },
    validate (...args) {
      this.$refs[this.name] && this.$refs[this.name].validate(...args)
    },
    setErrors (errors) {
      this.$refs[this.name] && this.$refs[this.name].setErrors(errors)
      // console.log(this.errors)
    }
  }
}
</script>

<style scoped>

</style>
