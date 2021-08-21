
<template>
  <v-container
    class='fill-height'
    fluid
  >
    <app-row
      align='center'
      justify='center'
    >
      <v-col
        class='text-center'
        cols='12'
      >
        <div class='d-inline-flex'>
          <v-avatar
            max-width='100%'
            size='200'
          >
            <v-img
              :src='AppLogo'
              contain
              height='200'
              max-height='100%'
            ></v-img>
          </v-avatar>
        </div>
      </v-col>
      <v-col
        cols='12'
        md='8'
      >
        <app-form
          ref='form'
          v-slot='v'
          :errors='errors'
          :submit='login'
        >
          <v-card
            elevation='12'
            tile
          >
            <v-toolbar
              color='primary darken-2'
              dark
              flat
            >
              <v-toolbar-title>{{ $t('login') }}</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
              <app-text-input
                v-model='form.username'
                :label="$t('attributes.username')"
                name='username'
                prepend-icon='mdi-account'
                rules='required'
              />
              <app-text-input
                v-model='form.password'
                :append-icon='passwordTypeIcon'
                :label="$t('attributes.password')"
                :type='passwordType'
                name='password'
                prepend-icon='mdi-lock'
                rules='required'
                @click:append='showPassword = !showPassword'
              />
            </v-card-text>
            <v-card-actions>
              <app-btn
                :loading='loading'
                type='submit'
              >{{ $t('done') }}
              </app-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </app-form>
      </v-col>
    </app-row>
  </v-container>
</template>

<script>
export default {
  name: 'Login',
  props: {
    source: String
  },
  metaInfo () {
    return {
      title: this.$t('login')
    }
  },
  data () {
    return {
      form: {
        username: null,
        password: null
      },
      errors: {},
      loading: !1,
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
  },
  methods: {
    login () {
      this.loading = !0

      return this.$api.methods.auth.login(this.form).then(({ data }) => {
        if (data && data.token) {
          this.loginUser(data.token, data.data || {})
        } else
          this.loading = !1
      }).catch(({ response }) => {
        const { data } = response || {}
        if (data) {
          this.errors = data.errors || []
          this.alertError(data.message)
        }
        this.loading = !1
      })
    }
  }
}
</script>

<style scoped>

</style>
