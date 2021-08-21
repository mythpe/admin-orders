<template>
  <v-app>
    <app-system-bar></app-system-bar>
    <app-bar v-model='drawer'></app-bar>
    <app-navigation
      v-model='drawer'
      :expand-on-hover='expandOnHover'
      :mini='mini'
      @mini='mini = $event'
    ></app-navigation>
    <template v-if='appShowSnackbar'>
      <app-snackbar />
    </template>
    <v-main id='main-content'>
      <app-progress-linear />
      <v-fade-transition mode='out-in'>
        <router-view />
      </v-fade-transition>
      <slot></slot>
    </v-main>
    <app-main-footer />

    <v-btn
      v-show='topScreen'
      v-scroll='onScroll'
      :left='AppRtl'
      :right='!AppRtl'
      bottom
      color='primary'
      dark
      fab
      fixed
      @click='toTop'
    >
      <v-icon>keyboard_arrow_up</v-icon>
    </v-btn>
  </v-app>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex'

export default {
  name: 'App',
  methods: {
    setDrawer (v) {
      this.navigation = v
    },
    setMini (v) {
      this.mini = v
    },
    onScroll (e) {
      if (typeof window === 'undefined') return
      const top = window.pageYOffset || e.target.scrollTop || 0
      this.topScreen = top > 20
    },
    toTop () {
      this.goTo(0)
    }
  },
  data () {
    return {
      // navigation: !this.AppIsMobile,
      navigation: false,
      // mini: !this.AppIsMobile,
      mini: false,
      canExpandOnHover: !this.AppIsMobile,
      topScreen: false
    }
  },
  computed: {
    drawer: {
      get () {
        return this.navigation
      },
      set (v) {
        this.setDrawer(v)
      }
    },
    expandOnHover () {
      return !1
      return !this.AppIsMobile && this.canExpandOnHover
    },
    appShowSnackbar: {
      set (value) {
        mapMutations('snackbar', ['setShow']).setShow.call(this, value)
      },
      get () {
        return mapGetters('snackbar', ['getShow']).getShow.call(this)
      }
    }
  },
  mounted () {
    // this.navigation = !this.AppIsMobile;
    // this.mini = !this.AppIsMobile;
  }
}
</script>
