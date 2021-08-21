

import { kebabCase } from 'lodash'

export default {
  methods: {
    route (name) {
      name = kebabCase(name)
      return { name }
    },
    isRoute (name) {
      return this.$route.name === name
    }
  },
  computed: {
    AppName () {
      return process.env.MIX_APP_NAME
    },
    AppLogo () {
      let e = require(`@images/${this.themeColor}.png`)
      return e.default || e
    }
  }
}
