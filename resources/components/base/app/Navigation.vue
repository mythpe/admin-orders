
<template>
  <v-navigation-drawer
    v-if='isLogin'
    v-bind='$attrs'
    v-on='$listeners'
    :expand-on-hover='expandOnHover'
    :mini-variant.sync='miniComputed'
    :right='AppRtl'
    :value='value'
    app
    clipped
  >
    <v-sheet
      v-if='isFetchItems'
      class='pa-0 pt-5'
      color='transparent'
    >
      <v-responsive
        class='mx-auto'
        max-width='100%'
      >
        <template v-for='i in 7'>
          <v-skeleton-loader
            class='mx-auto mb-1'
            type='list-item-avatar'
          ></v-skeleton-loader>
        </template>
      </v-responsive>
    </v-sheet>
    <app-drawer-list
      v-else
      :items='items'
    />

    <template v-slot:prepend>
      <v-sheet
        v-if='loadingUser'
        class='pa-0'
        color='transparent'
      >
        <v-responsive
          class='mx-auto'
          max-width='100%'
        >
          <v-skeleton-loader
            class='mx-auto mb-1'
            type='list-item-avatar-two-line'
          ></v-skeleton-loader>
        </v-responsive>
      </v-sheet>
      <v-list-item
        v-else
        class='px-2'
        two-line
      >
        <v-list-item-avatar>
          <v-img
            v-if='user.avatar'
            :src='user.avatar'
          ></v-img>
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-title>{{ user.name }}</v-list-item-title>
          <v-list-item-subtitle v-if='user.last_login'>{{ user.last_login }}</v-list-item-subtitle>
          <v-list-item-subtitle v-if='user.job_id_to_string'>{{ user.job_id_to_string }}</v-list-item-subtitle>
          <v-list-item-subtitle v-if='user.profile_type_to_string'>{{ user.profile_type_to_string }}</v-list-item-subtitle>
        </v-list-item-content>

        <v-btn
          v-if='!expandOnHover'
          icon
          @click.stop='clickClose'
        >
          <v-icon v-text='`mdi-chevron-${AppAlign}`'></v-icon>
        </v-btn>
      </v-list-item>
      <v-divider></v-divider>
    </template>

    <template v-slot:append>
      <v-divider></v-divider>

      <v-sheet
        v-if='loadingUser'
        class='pa-0'
        color='transparent'
      >
        <v-responsive
          class='mx-auto'
          max-width='100%'
        >
          <v-skeleton-loader
            class='mx-auto mb-1'
            type='list-item-avatar-two-line'
          ></v-skeleton-loader>
        </v-responsive>
      </v-sheet>

      <v-list-item
        v-else
        class='px-2'
        link
        two-line
        @click.stop='logout'
      >
        <v-list-item-avatar>
          <v-icon>mdi-logout</v-icon>
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-action-text v-text="$t('logout')">
            <!--            <v-btn :loading="logoutLoading" @click="logout" block>{{ $t('logout') }}</v-btn>-->
          </v-list-item-action-text>
        </v-list-item-content>
      </v-list-item>
      <v-container fluid>
        <!--        <p class="drawer-footer"> 2018—{{ $moment().format('YYYY') }} — <strong>MyTh</strong></p>-->
      </v-container>
    </template>
  </v-navigation-drawer>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex'

export default {
  name: 'Navigation',
  props: {
    value: {
      type: Boolean,
      default () {
        return !this.mini
      }
    },
    expandOnHover: {
      type: Boolean,
      default () {
        return !0
      }
    },
    mini: {
      type: Boolean,
      default () {
        return this.AppIsMobile
      }
    }
  },
  data () {
    return {
      logoutLoading: !1
    }
  },
  computed: {
    ...mapGetters('sideMenu', { items: 'getItems' }),
    miniComputed: {
      get () {
        // if (this.AppIsMobile && this.mini) {
        //   const mini = !1;
        //   this.setMini(mini)
        //   return mini;
        // }
        return this.mini
      },
      set (v) {
        this.setMini(v)
      }
    },
    isFetchItems: {
      set (v) {
        mapMutations('sideMenu', ['setFetching']).setFetching.call(this, v)
      },
      get () {
        return mapGetters('sideMenu', ['getFetching']).getFetching.call(this)
      }
    },
    user () {
      return this.$root.authUser
    },
    loadingUser () {
      return !Boolean(this.user)
    }
  },
  methods: {
    clickClose () {
      this.AppIsMobile && this.setDrawer(!this.value) || this.setMini(!this.miniComputed)
    },
    setDrawer (v) {
      this.$emit('input', Boolean(v))
    },
    setMini (v = !1) {
      this.$emit('mini', Boolean(v))
    },
    logout () {
      this.setDrawer(!1)
      this.logoutLoading = !0
      this.$emit('drawer', false)
      this.confirm(this.$t('messages.sure_logout'), () => {
        this.$api.methods.auth.logout().finally(() => this.logoutUser(true))
      }, () => {
        this.logoutLoading = !1
      })
    }
  }
}
</script>

<style scoped>
.drawer-footer {
    margin: 0;
    white-space: nowrap;
}
</style>
