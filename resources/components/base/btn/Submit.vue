
<template>
  <app-btn
    v-bind='$attrs'
    v-on='$listeners'
    :color='color'
    type='submit'
  >
    <slot></slot>
  </app-btn>
</template>

<script>
export default {
  name: 'Submit',
  props: {
    color: {
      default: () => 'primary'
    }
  }
}
</script>
