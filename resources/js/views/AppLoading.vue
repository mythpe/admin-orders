
<template>
  <v-app>
    <v-main>
      <v-container
        v-if='!$root.IniApp'
        class='fill-height'
      >
        <app-row
          align='center'
          justify='center'
        >
          <v-spacer></v-spacer>
          <v-col
            cols='12'
            sm='4'
          >
            <v-img
              :class='imgClesses'
              :src='imgSrc'
              :width='itemsWidth'
              contain
              max-width='100%'
            ></v-img>
            <v-progress-linear
              :height='30'
              :width='itemsWidth'
              buffer-value='0'
              class='app-progress-linear'
              color='black lighten-2'
              dark
              stream
            ></v-progress-linear>
          </v-col>
          <v-spacer></v-spacer>
        </app-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: 'AppLoading',
  data () {
    return {
      itemsWidth: 200,
      imgClesses: [
        'ma-auto',
        'mb-2'
      ].join(' '),
      imgSrc: require('@images/logo.png')
    }
  },
  created () {
    setTimeout(() => this.$root.IniApp = !0, 1500)
  }
}
</script>

<style lang='sass'>
.progress-linear
  max-width: 250px

</style>
