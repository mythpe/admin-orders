
<template>
  <div>
    <template v-if='showLogin'>
      <v-btn
        v-if='!isRoute(routes.login)'
        :to='route(routes.login)'
        class='mx-2'
        text
        tile
      >
        {{ $t('login') }}
      </v-btn>
    </template>
  </div>
</template>

<script>
export default {
  name: 'GuestButtonsBar',
  data () {
    return {
      routes: this.$config.routes.auth,
      landingRoutes: this.$config.routes.landing
    }
  },
  computed: {
    showLogin () {
      let login = this.$root.isLogin
      let error = this.$route.matched.some(r => r.meta.pageError)
      // console.log(login);
      // console.log(this.$root.isLogin , !this.$route.matched.some(r => r.meta.pageError));
      return error ? false : !login
    }
  }
}
</script>
