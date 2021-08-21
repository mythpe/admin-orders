
<template>
  <div>

    <v-menu
      :close-on-click='closeOnClick'
      :close-on-content-click='closeOnContentClick'
      :left='!AppRtl'
      :right='AppRtl'
      bottom
      offset-y
      transition='scale-transition'
    >
      <template v-slot:activator='{ attrs, on }'>
        <v-btn
          v-bind='attrs'
          v-on='on'
          icon
        >
          <v-badge
            :color='badgeColor'
            left
            overlap
          >
            <template
              v-if='unreadCount'
              v-slot:badge
            >
              <span>{{ unreadCount }}</span>
            </template>
            <v-icon>mdi-bell</v-icon>
          </v-badge>
        </v-btn>
      </template>
      <template v-if='fetching'>
        <app-skeleton
          :loading='fetching'
          :min-width='minWidth'
        />
      </template>
      <template v-else>
        <v-list
          :min-width='minWidth'
          :width='width'
          nav
          two-line
        >
          <template v-if='hasNotifications'>
            <template v-for='(notification) in notificationsComputed'>
              <v-hover v-slot='{hover}'>
                <v-list-item
                  :class="{ 'secondary elevation-12': hover, 'accent':!hover&&notification.unread}"
                  link
                  @click='clickNotification(notification)'
                >
                  <v-list-item-avatar>
                    <v-icon>{{ notification.icon }}</v-icon>
                  </v-list-item-avatar>
                  <v-list-item-content>
                    <v-list-item-title>{{ notification.title }}</v-list-item-title>
                    <v-list-item-subtitle>{{ notification.text }}</v-list-item-subtitle>
                  </v-list-item-content>

                  <!--              <v-list-item-icon v-if="notification.icon">-->
                  <!--                <v-icon small>{{ notification.icon }}</v-icon>-->
                  <!--              </v-list-item-icon>-->
                  <!--              <v-list-item-content>{{ notification.text }}</v-list-item-content>-->
                  <!--              <v-list-item-content>item-content</v-list-item-content>-->
                  <!--              <v-list-item-subtitle class="text-start">subtitle</v-list-item-subtitle>-->
                  <!--              <app-notification-item>-->
                  <!--                {{ notification.text }}-->
                  <!--                <v-list-item-title class="text-start"></v-list-item-title>-->
                  <!--              </app-notification-item>-->
                </v-list-item>
              </v-hover>
            </template>
          </template>
          <template v-else>
            <v-subheader>{{ $t('none') }}</v-subheader>
          </template>
        </v-list>
      </template>
    </v-menu>
    <v-dialog
      v-model='dialog.show'
      max-width='950'
    >
      <v-card>
        <app-card
          :title='dialog.title'
          fluid
        >
          <app-container>
            <p>{{ dialog.text }}</p>
          </app-container>
          <template #actions>
            <app-btn @click='closeDialog'>
              {{ $t('close') }}
            </app-btn>
          </template>
        </app-card>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
// Components
import { VHover, VListItem } from 'vuetify/lib'

export default {
  name: 'NotificationsMenu',

  components: {
    AppNotificationItem: {
      render (h) {
        return h(VHover, {
          scopedSlots: {
            default: ({ hover }) => {
              return h(VListItem, {
                attrs: this.$attrs,
                class: {
                  'black--text': !hover,
                  'white--text secondary elevation-12': hover
                },
                props: {
                  activeClass: '',
                  dark: hover,
                  link: true,
                  ...this.$attrs
                }
              }, this.$slots.default)
            }
          }
        })
      }
    }
  },
  data () {
    return {
      closeOnClick: !0,
      closeOnContentClick: !1,
      minWidth: '200px',
      width: '300',
      unreadCount: undefined,
      fetched: !1,
      fetching: !1,
      fetchedUnreadCount: !1,
      fetchingUnreadCount: !1,
      notifications: [],
      dialog: {
        show: !1,
        title: '',
        text: ''
      }
    }
  },
  computed: {
    badgeColor () {
      return this.unreadCount > 0 ? 'error' : 'transition'
    },
    notificationsComputed: {
      get () {
        return this.notifications
      },
      set (v) {
        this.notifications = v
      }
    },
    hasNotifications () {
      return this.notifications.length > 0
    }
  },
  methods: {
    clickNotification (notification) {
      this.showDialog(notification.title, notification.message)
      this.notifications.map(e => {
        if (e.id === notification.id) {
          e.unread = false
          e.read = true
        }
        return e
      })
      this.unreadCount > 0 && --this.unreadCount
      this.$api.methods.notification.markAsRead(notification.id).then(({ result }) => {
        result && result.unreadCount && (this.unreadCount = result.unreadCount)
      })
    },
    showDialog (title = '', text = '') {
      this.dialog.show = !0
      this.dialog.text = text
      this.dialog.title = title
    },
    closeDialog () {
      this.dialog.show = !1
      this.dialog.text = ''
      this.dialog.title = ''
    },
    fetchNotifications () {
      // if(this.fetching || this.fetched) return;
      if (this.fetching) return
      this.fetching = !0

      return this.$api.methods.notification.index({ limit: 15 }).then(({ data }) => {
        let notifications = []
        if (data && data.success && data.data) {
          // this.fetched = !0;
          data = data.data
          notifications = data.notifications
          this.unreadCount = data.unreadCount
        }
        this.notifications = notifications
        // setTimeout(this.fetchUnreadCount, 500);
      }).finally(() => this.fetching = !1)
    },
    fetchUnreadCount () {
      if (this.fetchingUnreadCount || this.fetchedUnreadCount) return
      this.fetchingUnreadCount = !0

      return this.$api.methods.notification.indexUnreadCount().then(({ data }) => {
        let r
        if (data && (r = data.data)) {
          this.fetchedUnreadCount = !0
          this.unreadCount = parseInt(r.count) || null
        }
      }).finally(() => this.fetchingUnreadCount = !1)
    },
    ini () {
      if (this.fetched && this.fetchedUnreadCount)
        return
      this.fetchNotifications()
      setInterval(this.fetchNotifications, (10 * 1000))
    }
  },
  watch: {
    '$root.isLogin': {
      handler (newVal) {
        newVal && this.ini()
      },
      deep: !0,
      immediate: !0
    }
  },
  mounted () {
    // console.log(1)
  }
}
</script>
<style
  lang='scss'
  scoped
>
.theme--dark.v-list-item:not(.v-list-item--active):not(.v-list-item--disabled).active-notification {

  &:hover {
    color: var(--v-secondary-base) !important;
  }
}
</style>
