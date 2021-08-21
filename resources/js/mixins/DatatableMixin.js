
export default {
  data () {
    const apiMethods = this.$api.methods[this.$options.helperApiName]
    return {
      apiMethods,
      selectedItem: {},

      dialog: false,
      loadingShowDialog: false,
      loadingDeleteItem: false,
      formDialogLoading: false,
      formErrors: {}
    }
  },
  watch: {
    dialog (v) {
      // console.log('dialog',this.selectedItem);
      if (!v) {
        this.setAxiosCountdown()
        this.resetForm()
        this.clearSelectedItem()
      } else {
        this.setDefaultSelectedItem()
      }
    }
  },
  methods: {
    refreshDatatable () {
      this.$refs.datatable && this.$refs.datatable.$emit('refresh')
    },

    updateOneItemData (id, data) {
      this.$root.showAxiosErrorMessage = !1
      return this.apiMethods.update(id, data, { params: { singleItem: 1 } })
    },

    showEditDialog (item = {}) {
      // this.showDatatableDialog();
      this.$nextTick(() => this.fetchItemData(item.id))
    },

    setSelectedItem (item) {
      this.selectedItem = item
    },

    getShowMethod (...args) {
      return this.apiMethods.show(...args)
    },

    fetchItemData (id) {
      if (this.loadingShowDialog) return
      this.loadingShowDialog = !0
      return this.getShowMethod(id).then((r) => {
        const { data } = r || {}
        this.showDatatableDialog()
        data && data.data && this.setSelectedItem(data.data)
        return r
      }).finally(() => this.loadingShowDialog = !1)
    },

    deleteItem (item) {
      this.confirm(this.$t('messages.confirmDelete'), () => {
        this.loadingDeleteItem = !0
        this.apiMethods.destroy(item.id).then(({ data }) => {
          data && this.alertSuccess(data.message)
          data && data.success && this.refreshDatatable()
        }).catch(({ response }) => {
          response && response.data && this.alertError(response.data.message)
        }).finally(() => this.loadingDeleteItem = !1)
      })
    },

    showDatatableDialog () {
      this.dialog = !0
      this.resetForm()
      this.clearSelectedItem()
    },

    closeDatatableDialog () {
      // console.log(1);
      this.dialog = !1
      this.resetForm()
      this.clearSelectedItem()
    },

    clearSelectedItem () {
      this.selectedItem = { ...this.defaultSelectedItem }
      this.formDialogLoading = !1
    },

    setDefaultSelectedItem () {
      this.selectedItem = { ...this.defaultSelectedItem, ...this.selectedItem }
    },

    setErrors (errors = {}) {
      errors = this.$helpers.isOnlyObject(errors) ? errors : {}
      this.getForm() && this.getForm().setErrors(errors)
      this.formErrors = errors
    },

    getForm () {
      return this.$refs.form
    },

    resetForm () {
      this.getForm() && this.getForm().reset()
      this.setErrors({})
    },

    editMode () {
      const item = this.selectedItem
      return item && item.id
    },

    submitForm () {
      if (this.formDialogLoading) return
      this.formDialogLoading = !0
      const action = this.editMode() ?
        this.apiMethods.update(this.getItemId, this.selectedItem) :
        this.apiMethods.store(this.selectedItem)
      this.filterSelectedItem()
      // console.log(this.selectedItem);
      action.then(({ data }) => {
        if (data && data.success) {
          this.alertSuccess(data.message)
          this.closeDatatableDialog()
          this.$nextTick(() => this.refreshDatatable())
        }
      }).catch(({ response }) => {
        this.resetForm()
        const { data } = response || {}
        data && data.message && this.alertError(data.message)
        this.setErrors(data.errors || {})
      }).finally(() => this.formDialogLoading = !1)
    },

    filterSelectedItem () {
      Object.keys(this.selectedItem).forEach(k => {
        this.selectedItem[k] === undefined && (this.selectedItem[k] = null)
      })
    }

  },
  computed: {
    datatableDialogTitle () {
      return this.$t((this.isReadOnly ? 'view' : (!this.selectedItem.id ? 'create' : 'edit')))
    },

    getItemId () {
      return this.selectedItem.id
    },

    isNewItem () {
      return this.selectedItem.id === undefined || this.selectedItem.id === null
    },

    isOldItem () {
      return this.selectedItem.id !== undefined && this.selectedItem.id !== null
    },

    defaultSelectedItem () {
      return {}
    },

    tableUrl () {
      return this.$helpers.isFunction(this.apiMethods.index) ? this.apiMethods.index(!0) : this.apiMethods.index
    },

    isReadOnly () {
      return false
    }
  }
}
