

export default {
  methods: {
    startProgress () {
      this.$root.AppProgress = !0
    },
    stopProgress () {
      this.$root.AppProgress = !1
    },
    setFullscreen (v) {
      this.$root.AppFullscreen = v
    }
  },
  beforeCreate () {
    if (!this.$root.$createInterceptors) {
      this.$axios.interceptors.request.use(config => {
        this.startProgress()
        return config
      }, error => {
        this.stopProgress()
        return Promise.reject(error)
      })

      this.$axios.interceptors.response.use(response => {
        this.stopProgress()
        return response
      }, error => {
        this.stopProgress()
        return Promise.reject(error)
      })
      this.$root.$createInterceptors = true
    }
  }
}
