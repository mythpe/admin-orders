
<template>
  <v-system-bar
    :class='getClasses'
    :dark='themeLight'
    app
  >
    <span :class='spanClass'>{{ day }}</span>
    <span :class='spanClass'>{{ date }}</span>
    <span :class='spanClass'>{{ hijri }}</span>
    <span>{{ time }}</span>
    <v-spacer></v-spacer>
    <template v-if='$root.canFullscreen && !$vuetify.breakpoint.xsOnly'>
      <app-system-bar-btn
        v-if='!$root.AppFullscreen'
        @click='$root.setFullscreen(!0)'
      >
        <v-icon>mdi-fullscreen</v-icon>
      </app-system-bar-btn>
      <app-system-bar-btn
        v-if='$root.AppFullscreen'
        @click='$root.setFullscreen(!1)'
      >
        <v-icon>mdi-fullscreen-exit</v-icon>
      </app-system-bar-btn>
    </template>
    <app-system-bar-btn @click='$root.themeDark = !$root.themeDark'>
      <v-icon>mdi-palette</v-icon>
    </app-system-bar-btn>
    <app-system-bar-btn
      v-if='isLogin'
      @click='closeWindow'
    >
      <v-icon>mdi-close</v-icon>
    </app-system-bar-btn>
    <!--    <v-spacer></v-spacer>-->
    <!--    <span>{{ date }}</span>-->
    <!--    <v-spacer></v-spacer>-->
    <!--    <span>{{ hijri }}</span>-->
    <!--    <v-spacer></v-spacer>-->
  </v-system-bar>
</template>

<script>

export default {
  name: 'SystemBar',
  data () {
    return {
      hijri: null,
      date: null,
      time: null,
      day: null,
      spanClass: 'pe-2'
    }
  },
  computed: {
    getClasses () {
      return {
        primary: true,
        'darken-2': this.themeLight,
        'darken-1': this.themeDark
      }
    }
  },
  created () {
    let time, date, day, hijri
    setInterval(() => {
      time = this.$moment().format('hh:mm:ss a')
      date = this.$moment().format('YYYY/MM/DD')
      day = this.$moment().format('ddd')
      hijri = this.$moment().format('iYYYY/iMM/iDD')

      this.date = `${date}`
      this.hijri = `${hijri}`
      this.time = time
      this.day = day
    }, 1000)
  },
  methods: {
    closeWindow () {
      this.confirm(this.$t('messages.sure_logout'), () => {
        // window.location.href = routeAllies.auth.logout;
        this.logoutUser(!0)
      })
    }
  }
}
</script>

<style scoped></style>
