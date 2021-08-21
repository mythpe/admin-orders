
<template>
  <app-text-input
    v-bind='$attrs'
    v-on='$listeners'
    :append-icon='passwordTypeIcon'
    :name='name'
    :type='passwordType'
    :value='value'
    :vid='vid'
    prepend-icon='mdi-lock'
    v-on:input="$emit('input',$event)"
    @click:append='showPassword = !showPassword'
  />
</template>

<script>

export default {
  name: 'PasswordInput',
  props: {
    value: {},
    vid: {},
    name: {
      required: true
    }
  },
  data () {
    return {
      showPassword: !1
    }
  },
  computed: {
    passwordType () {
      return this.showPassword ? 'text' : 'password'
    },
    passwordTypeIcon () {
      return this.showPassword ? 'mdi-eye-off' : 'mdi-eye'
    }
  }
}
</script>
