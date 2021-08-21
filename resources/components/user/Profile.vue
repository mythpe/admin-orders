
<template>
  <app-container fluid>
    <app-row justify='center'>
      <v-col
        md='10'
        order='2'
        order-sm='1'
      >
        <app-card
          :title="$t('profile.update')"
          icon='mdi-pen'
        >
          <app-form
            :errors='accountErrors'
            :submit='submitAccount'
          >
            <app-row>
              <app-text-input
                v-model='user.name'
                md='6'
                name='name'
                rules='required'
              />
              <app-text-input
                v-model='user.username'
                md='6'
                name='username'
                rules='required'
              />
              <!--              <app-text-input-->
              <!--                  v-model="user.email"-->
              <!--                  md="6"-->
              <!--                  name="email"-->
              <!--                  rules=""-->
              <!--              />-->
              <!--              <app-text-input-->
              <!--                  v-model="user.mobile"-->
              <!--                  md="6"-->
              <!--                  name="mobile"-->
              <!--                  rules="required"-->
              <!--              />-->
            </app-row>
            <app-row>
              <app-password-input
                v-model='user.password'
                autocomplete='new-password'
                md='6'
                name='password'
              />
              <app-password-input
                v-model='user.password_confirmation'
                md='6'
                name='password_confirmation'
                rules='confirmed:password'
              />
            </app-row>
            <app-row>
              <app-col all='12'>
                <app-submit :loading='loading'>{{ $t('save') }}</app-submit>
              </app-col>
            </app-row>
          </app-form>
        </app-card>
      </v-col>
    </app-row>
  </app-container>
</template>

<script>
import MetaInfoMixin from '@mixins/MetaInfoMixin'

export default {
  name: 'Profile',
  data () {
    return {
      loading: !1,
      accountErrors: {}
    }
  },
  computed: {
    user: {
      get () {
        // console.log(this.$root.authUser)
        return this.$root.authUser || {}
      },
      set (v) {
        this.$root.authUser = v
      }
    }
  },
  methods: {
    saved ({ data }) {
      if (data && data.data && data.success === true) {
        this.$root.updateProfile(data.data)
      }
    },
    submitAccount (e) {
      this.loading = !0
      this.$api.methods.user.updateProfile(this.$root.authUser).then(({ data }) => {
        data && data.message && this.alertSuccess(data.message)
      }).catch(({ response }) => {
        this.accountErrors = response.data.errors || {}
      }).finally(() => this.loading = !1)
    }
  },
  mixins: [MetaInfoMixin]
}
</script>
