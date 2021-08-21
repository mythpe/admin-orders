

export default {
  computed: {
    getHeaders () {
      return this.parseHeaders()
    }
  },
  methods: {
    parseHeaders (headers = null) {
      headers = headers || this.headers
      const _ = this.$_
      return headers.map((i) => {
        let key = _.isObject(i) ? i.text : i
        let c

        if (_.endsWith(key, (c = 'ToString'))) {
          key = key.substr(0, key.length - c.length)
        } else if (_.endsWith(key, (c = '_to_string'))) {
          key = key.substr(0, key.length - c.length)
        } else if (_.endsWith(key, (c = '_to_yes'))) {
          key = key.substr(0, key.length - c.length)
        }

        const text = this.parseAttribute(key)
        const align = this.center ? 'center' : undefined
        const value = _.isObject(i) ? i.value : i
        const original = _.isObject(i) ? { ...i } : {}

        if (value === this.controlKey) {
          original.width = this.controlWidth
          original.sortable = !1
          original.filterable = !1
        } else if (value === this.itemKey) {
          original.width = this.itemKeyWidth
        }
        // original.divider = !1

        let header = { align, ...original, text, value }

        if (key === 'order_by') {
          header.width = header.width || '10%'
        }
        // console.log(header);
        return header
      })
    }
  }
}
