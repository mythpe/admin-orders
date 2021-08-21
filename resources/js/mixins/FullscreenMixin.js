

export default {
  methods: {
    toggleFullscreen () {
      this.$root.AppFullscreen = !this.$root.AppFullscreen
    }
  },
  watch: {
    AppFullscreen (v) {
      if (this.canFullscreen) {
        v && window.document.documentElement.requestFullscreen() ||
        window.document.exitFullscreen()
      }
    }
  },
  computed: {
    canFullscreen () {
      return window && window.document.fullscreenEnabled
    }
  }
}
