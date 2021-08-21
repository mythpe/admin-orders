

export default {
  name: 'DatatableEditDialog',
  props: {
    value: {},
    type: {
      type: String,
      default: 'text',
      validator (type) {
        const types = ['text', 'number', 'price', 'float']
        const r = types.indexOf(type) >= 0
        if (!r) {
          console.error('Available types is: ' + types.join(', '))
        }
        return r
      }
    },
    persistent: {
      type: Boolean,
      default: false
    },
    large: {
      type: Boolean,
      default: true
    },
    item: {
      type: Object,
      require: true
    },
    name: {
      require: true
    },
    cancelText: {
      type: String,
      default: () => 'cancel'
    },
    saveText: {
      type: String,
      default: () => 'save'
    },
    updateMethod: {
      type: Function,
      require: true
    }
  },
  data () {
    return {
      inputValue: undefined,
      errors: {}
    }
  },
  methods: {
    iniComp () {
      this.haveErrors || (this.inputValue = this.value)
    },
    openEditDialog () {
      const ref = this.$refs.editDialog
      ref && (ref.isActive = !0)
    },
    updateValue () {
      this.$emit('input', this.inputValue)
    },

    saved () {
      this.$emit('refresh')
    },
    save () {
      this.errors = {}
      if (this.updateMethod) {
        const params = { [this.name]: this.inputValue }

        this.updateMethod(this.item.id, params).then(({ data }) => {
          if (data && data.success === true) {
            const a = this.alertSuccess(data.message)
            this.updateValue()
            this.errors = {}
            a.finally(() => this.saved())
          }
        }).catch(({ response }) => {
          const { data } = response || {}
          // data && data.message && this.alertError(data.message);
          this.errors = data && data.errors || {}
          this.$nextTick(() => this.openEditDialog())
        })
      }

    },
    cancel () {
      this.$emit('cancel', this.item)
    },
    open () {
      this.iniComp()
      this.$emit('open', this.item)
    },
    close () {
      this.$emit('close', this.item)
      this.errors = {}
    }
  },
  mounted () {
    this.iniComp()
  },
  computed: {
    haveErrors () {
      return this.componentErrors.length > 0
    },
    componentErrors () {
      let name = this.name;
      ['_to_yes', '_to_string', 'ToString'].forEach((v) => name.substr(-v.length) === v && (name = name.split(v)[0]))
      return this.errors && name && this.errors[name] ? this.errors[name] : []
    },
    componentName () {
      return `app-${this.type.toLowerCase()}-input`
    },
    haveSlot () {
      return this.$scopedSlots['input'] !== undefined
    },
    getSaveText () {
      return this.parseAttribute(this.saveText)
    },
    getCancelText () {
      return this.parseAttribute(this.cancelText)
    }
  }
}
