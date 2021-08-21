
<template>
  <div v-if='appShowSnackbar'>
    <component
      :is='getComponentName'
      :value='appShowSnackbar'
      no-click-animation
      persistent
      scrollable
      transition
    >
      <v-snackbar
        v-model='appShowSnackbar'
        v-bind='$attrs'
        v-on='$listeners'
        :color='color'
        :content-class='contentClasses'
        :elevation='elevation'
        :timeout='timeout'
        :transition='transition'
        dark
        left
        max-width='200'
        top
        vertical
      >
        <div>
          <template v-if='iconStyle'>
            <v-icon
              :color='iconColor'
              class='mb-2'
            >{{ iconStyle }}
            </v-icon>
          </template>
          <span v-html='appTextSnackbar'></span>
        </div>
        <template v-slot:action='{ attrs }'>
          <template v-if='appConfirm'>
            <snackbar-btn
              :color='btnColor'
              @click='closeSnackbar'
            >
              {{ $t('yes') }}
            </snackbar-btn>
            <snackbar-btn
              :color='btnColor'
              @click='cancelSnackbar'
            >
              {{ $t('no') }}
            </snackbar-btn>
          </template>
          <template v-else>
            <snackbar-btn
              :color='btnColor'
              @click='closeSnackbar'
            >
              {{ $t('done') }}
            </snackbar-btn>
          </template>
        </template>
      </v-snackbar>
    </component>
  </div>
</template>

<script>
import { VDialog } from 'vuetify/lib/components/VDialog'
import { createNamespacedHelpers } from 'vuex'
import SnackbarBtn from './SnackbarBtn'

const { mapGetters, mapActions, mapMutations } = createNamespacedHelpers('snackbar')

export default {
  name: 'Snackbar',
  components: {
    // SnackbarBtn: () => import( /* webpackChunkName: "SnackbarBTN" */ './SnackbarBtn' )
    SnackbarBtn
  },
  data () {
    return {
      elevation: 24,
      SnackbarTimeout: -1,
      transition: 'fade-transition'
    }
  },
  watch: {
    appShowSnackbar (n, o) {
      n === false && o === true && this.closeSnackbar()
    }
  },
  methods: {
    cancelSnackbar () {
      if (this.appShowSnackbar === true) {
        this.appShowSnackbar = false
        if (this.appCallbackReject) {
          this.appCallbackReject.call(this)
        }
        this.$nextTick(() => this.closedSnackbar())
      }
    },
    closeSnackbar () {
      if (this.appShowSnackbar === true) {
        this.appShowSnackbar = false
        if (this.appCallbackSnackbar) {
          this.appCallbackSnackbar.call(this)
        }
        this.$nextTick(() => this.closedSnackbar())
      }
    },
    closedSnackbar () {
      this.$nextTick(() => mapActions(['hideSnackbar']).hideSnackbar.call(this))
    }
  },
  computed: {
    timeout () {
      return this.appConfirm ? -1 : (this.isToast ? 3000 : this.SnackbarTimeout)
    },
    contentClasses () {
      return [
        'text-start',
        'w-100',
        this.appTypeSnackbar === 'normal' ? 'black--text' : 'white--text'
      ].join(' ')
    },
    color () {
      return this.mapStyle[this.appTypeSnackbar].snackbar || ''
    },
    btnColor () {
      return this.mapStyle[this.appTypeSnackbar].btnColor
    },
    mapStyle () {
      return this.appTypesSnackbar
    },
    iconColor () {
      return this.mapStyle[this.appTypeSnackbar].iconColor || ''
    },
    iconStyle () {
      return this.mapStyle[this.appTypeSnackbar].icon || ''
    },
    appShowSnackbar: {
      set (value) {
        mapMutations(['setShow']).setShow.call(this, value)
      },
      get () {
        return mapGetters(['getShow']).getShow.call(this)
      }
    },
    appTextSnackbar: {
      set (value) {
        mapMutations(['setText']).setText.call(this, value)
      },
      get () {
        return mapGetters(['getText']).getText.call(this)
      }
    },
    appTypeSnackbar: {
      set (value) {
        mapMutations(['setType']).setType.call(this, value)
      },
      get () {
        return mapGetters(['getType']).getType.call(this)
      }
    },
    appTypesSnackbar () {
      return mapGetters(['getTypes']).getTypes.call(this)
    },
    appCallbackSnackbar: {
      set (value) {
        mapMutations(['setCallback']).setCallback.call(this, value)
      },
      get () {
        return mapGetters(['getCallback']).getCallback.call(this)
      }
    },
    appConfirm: {
      set (value) {
        mapMutations(['setConfirm']).setConfirm.call(this, value)
      },
      get () {
        return mapGetters(['getConfirm']).getConfirm.call(this)
      }
    },
    appCallbackReject: {
      set (value) {
        mapMutations(['setReject']).setReject.call(this, value)
      },
      get () {
        return mapGetters(['getReject']).getReject.call(this)
      }
    },
    isToast () {
      return mapGetters(['getToast']).getToast.call(this)
    },
    getComponentName () {
      return this.isToast ? 'span' : VDialog
    }
  }
  // mixins: [SnackbarMixins]
}
</script>

<style scoped>

</style>
