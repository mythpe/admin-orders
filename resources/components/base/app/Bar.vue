
<template>
  <v-app-bar
    v-bind='$attrs'
    v-on='$listeners'
    :clipped-left='!AppRtl'
    :clipped-right='AppRtl'
    app
    color='primary'
  >
    <v-app-bar-nav-icon
      v-if='showDrawerIcon'
      @click.stop='drawer = !drawer'
    ></v-app-bar-nav-icon>
    <!--<v-avatar :color='logoColor'>-->
    <!--  <v-img-->
    <!--    :src='AppLogo'-->
    <!--    contain-->
    <!--  ></v-img>-->
    <!--</v-avatar>-->
    <!--<v-toolbar-title class='ps-4'>-->
    <!--  <router-link-->
    <!--    :to='route($config.routes.landing.home)'-->
    <!--    class='pointer'-->
    <!--    tag='span'-->
    <!--  >{{ AppName }}-->
    <!--  </router-link>-->
    <!--</v-toolbar-title>-->
    <app-guest-buttons-bar />
    <v-spacer></v-spacer>
    <v-btn
      v-show='user'
      :to='route($config.routes.user.profile)'
      icon
    >
      <v-icon>mdi-account</v-icon>
    </v-btn>
    <div class='mx-1'></div>
    <!--    <template v-if="user">-->
    <!--      <app-notifications-menu/>-->
    <!--      <div class="mx-1"></div>-->
    <!--    </template>-->
    <!--<app-language-menu />-->
  </v-app-bar>
</template>

<script>

export default {
  name: 'Bar',
  props: {
    value: {
      type: Boolean
    }
  },
  computed: {
    logoColor () {
      return this.themeDark ? 'gray' : 'white'
    },
    showDrawerIcon () {
      return this.isLogin
    },
    drawer: {
      get () {
        return this.value
      },
      set (v) {
        this.$emit('input', Boolean(v))
      }
    },
    user () {
      return this.$root.authUser
    }
  }
}
</script>
