
<template>
  <App>
    <v-container
      class='text-center'
      fill-height
      style='height: calc(100vh - 58px);'
    >
      <app-row align='center'>
        <v-col>
          <h1 class='display-2 primary--text'>{{ $t('messages.whoops') }}</h1>

          <p>{{ getMessage }}</p>

          <v-btn
            v-if='showHome'
            color='primary'
            outlined
            to='/'
          >
            {{ $t('home') }}
          </v-btn>
          <v-btn
            v-if='showLogout'
            :to='logoutRoute.path'
            color='primary'
            outlined
          >
            {{ $t('logout') }}
          </v-btn>
        </v-col>
      </app-row>
    </v-container>
  </App>
</template>

<script>
import routeAllies from '@routes/config'
import App from '@views/App'

export default {
  name: 'Error',
  components: { App },
  data () {
    return {
      logoutRoute: routeAllies.auth.logout
    }
  },
  props: {
    message: {
      type: String,
      default: () => undefined
    },
    showHome: {
      type: Boolean,
      default: () => true
    },
    showLogout: {
      type: Boolean,
      default: () => true
    }
  },
  computed: {
    getMessage () {
      let message = this.message || this.$route.params.message || ''
      return message ? this.parseAttribute(message) : this.$t('messages.not_found_message')
    }
  }
}
</script>
