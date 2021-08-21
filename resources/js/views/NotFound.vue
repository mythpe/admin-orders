
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
        </v-col>
      </app-row>
    </v-container>
  </App>
</template>

<script>
import App from '@views/App'

export default {
  name: 'NotFound',
  components: { App },
  props: {
    message: {
      type: String,
      default: () => undefined
    },
    showHome: {
      type: Boolean,
      default: () => true
    }
  },
  computed: {
    getMessage () {
      let message = this.message || this.$route.params.message || ''
      // console.log(this.message);
      return message ? this.parseAttribute(message) : this.$t('messages.not_found_message')
    }
  }
}
</script>
