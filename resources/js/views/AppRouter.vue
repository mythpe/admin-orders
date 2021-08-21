
<template>
  <v-fade-transition mode='out-in'>
    <router-view></router-view>
  </v-fade-transition>
</template>

<script>
export default {
  name: 'AppRouter'
}
</script>

<style>
.fade-enter-active, .fade-leave-active {
    transition: opacity .2s;
}

.fade-enter, .fade-leave-to {
    opacity: 0;
}

</style>
