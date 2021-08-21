
import GetHeadersMixin from '@mixins/GetHeadersMixin'
import { debounce } from 'lodash'

export default {
  name: 'Datatable',
  props: {
    fetchMethod: {
      type: [String, Function],
      default: () => 'get',
      validator: function (value) {
        return ['get', 'post', 'GET', 'POST'].indexOf(value) !== -1
      }
    },
    headers: {
      type: [Array],
      required: !0
    },
    url: {
      type: [String, Function],
      default: () => undefined
    },
    itemKey: {
      type: String,
      default: () => 'id'
    },
    itemKeyWidth: {
      type: [String, Number],
      default: () => '6%'
    },
    controlKey: {
      type: String,
      default: () => 'control'
    },
    controlWidth: {
      type: [String, Number],
      default: () => '20%'
    },
    fixedHeader: {
      type: Boolean,
      default: () => !0
    },
    footer: {
      type: Object,
      default () {
        return {
          'showFirstLastPage': true,
          'firstIcon': 'mdi-arrow-collapse-left',
          'lastIcon': 'mdi-arrow-collapse-right',
          'items-per-page-options': [50, 200, 500, -1]
        }
      }
    },
    showSelect: {
      type: Boolean,
      default: () => !0
    },
    singleSelect: {
      type: Boolean,
      default: () => !1
    },
    dense: {
      type: [Boolean],
      default: () => !1
    },
    multiSort: {
      type: Boolean,
      default: () => !0
    },
    search: {
      type: Boolean,
      default: () => !0
    },
    center: {
      type: Boolean,
      default: () => !1
    },
    minHeight: {
      type: [Number, String],
      default: () => 300
    },
    height: {
      type: [Number, String],
      default: () => 450
    },
    maxHeight: {
      type: [Number, String],
      default: () => 450
    },
    loaderHeight: {
      type: [Number, String],
      default: () => 5
    },
    formDialog: {
      type: Boolean,
      default: () => undefined
    },
    formDialogLoading: {
      type: Boolean,
      default: () => false
    },
    btnDialog: {
      type: Boolean,
      default: () => undefined
    },
    modalTitle: {
      type: String,
      default: () => 'store'
    },
    modalBtnText: {
      type: String,
      default: () => 'create'
    },
    modalBtnIcon: {
      type: String,
      // default: () => 'add'
      default: () => 'add'
    },
    pdf: {
      type: [String, Function],
      default: () => undefined
    },
    excel: {
      type: [String, Function],
      default: () => undefined
    },
    fullscreen: {
      type: Boolean,
      default: () => undefined
    },
    calculateWidths: {
      type: Boolean,
      default: () => true
    },
    expansion: {
      default: () => undefined
    }
  },
  data () {
    return {
      datatableOptions: {},
      datatableFilters: {},
      items: [],
      selectedItems: undefined,
      pageCount: undefined,
      serverItemsLength: undefined,
      expansionTopSlot: undefined,
      loading: !1,
      loadingPdf: !1,
      loadingExcel: !1,
      modal: !1,
      expansionLazy: !1
    }
  },
  watch: {
    datatableOptions: {
      handler: debounce(function () {
        this.fetchData()
      }, 300),
      deep: true,
      immediate: true
    },

    datatableFilters: {
      handler: debounce(function () {
        this.fetchData()
      }, 300),
      deep: true
    },

    modal (v) {
      this.$emit('update:formDialog', v)
    },

    formDialog (v) {
      this.modal = v
    }
  },
  computed: {

    computedUrlDatatable () {
      return this.getUrl('url')
    },

    getFetchMethod () {
      return this.$helpers.isFunction(this.fetchMethod) ? this.fetchMethod() : this.fetchMethod
    },

    getRequestDatatableHeaders () {
      return this.getHeaders.filter(h => h.value !== this.controlKey)
    },

    getScopedSlots () {
      return Object.keys(this.$scopedSlots).filter(i => ['top', 'formDialog', 'filter'].indexOf(i) < 0)
    },

    selectedIds () {
      const items = this.selectedItems || []
      return items.map((e) => e[this.itemKey])
    },

    computedExpansionTopSlot: {
      get () {
        const defIndex = 0
        if (this.expansion) {
          return this.expansion
        }
        if (this.expansionTopSlot) {
          return this.expansionTopSlot
        }
        if (!this.AppIsMobile) {
          return defIndex
        }
        return undefined
        // return this.expansionTopSlot;
        // console.log(this.expansionTopSlot)
        // if(this.expansionTopSlot === undefined) {
        //   return defIndex;
        // }
        // else
        //   return this.expansionTopSlot;
        // return 0;
        // return this.expansion === undefined ? (this.AppIsMobile ? undefined : defIndex) : this.expansion;
      },
      set (v) {
        this.expansionTopSlot = v
      }
    },

    hasTopSlot () {
      return this.search || this.$scopedSlots['filter'] || this.$scopedSlots['top'] || this.hasDialog
    },

    hasFilterSlot () {
      return this.$scopedSlots['filter']
    },

    canPdf () {
      return this.pdf !== undefined
    },

    canExcel () {
      return this.excel !== undefined
    },

    getModalBtnText () {
      return this.hasDialog && this.modalBtnText ? this.parseAttribute(this.modalBtnText) : null
    },

    getModalBtnIcon () {
      return this.hasDialog && this.modalBtnIcon ? this.modalBtnIcon : null
    },

    getModalBtnTooltip () {
      return this.getModalTitle
      // console.log(this.modalTitle,this.getPageTitle());
      // return '';
    },

    getModalTitle () {
      if (!this.hasDialog || !this.modalTitle) return null
      const title = this.modalTitle
      let n = this.getPageTitle(1)
      if (title === 'store' || title === 'update' || title === 'show') {
        title === 'store' && (n = this.parseArabicTitle(n))
        return this.$t(`replace.${title}`, { n })
      }
      // console.log(this.modalTitle);
      // return `${this.parseAttribute(this.modalTitle)} ${n}`;
      return this.parseAttribute(this.modalTitle)
      // return this.hasDialog && this.modalTitle ? this.parseAttribute(this.modalTitle) : null;
    },

    hasDialog () {
      return this.formDialog !== undefined
    },

    hasBtnDialog () {
      return this.btnDialog === false ? false : this.$scopedSlots['formDialog']
    },

    datatableListeners () {
      const vm = this
      return Object.assign({}, this.$listeners,
        {
          refresh: (...args) => vm.refresh(...args)
        })
    },

    slotListeners () {
      const vm = this
      return {
        refresh: (...args) => vm.refresh(...args)
      }
    },

    slotBind () {
      return {
        ...this.$attrs,
        datatableOptions: this.getDatatableOptions(),
        datatableFilters: this.getDatatableFilters(),
        on: this.slotListeners
      }
    },

    getFullscreen () {
      return this.fullscreen === undefined ? this.AppIsMobile : this.fullscreen
    },

    getSelectedLength () {
      return this.selectedItems ? (this.selectedItems.length || null) : null
    },

    getShowSelect () {
      return !this.canExcel && !this.canPdf && this.showSelect === undefined ? false : this.showSelect
    }
  },
  methods: {
    clickModalActivator () {
      this.modal = !this.modal
    },

    refresh () {
      this.fetchData()
    },

    iniDatatable () {
      this.modal = this.formDialog;
      (!this.hasFilterSlot || !this.search) && (this.expansionTopSlot = 0)
    },

    getDatatableOptions () {
      return this.datatableOptions
    },

    getDatatableFilters () {
      return this.datatableFilters
    },

    getStringifyOptions (params = {}) {
      const URLSearchParams = this.$helpers.queryStringify({ ...this.getDatatableOptions() })
      Object.keys(params).forEach((value) => {
        URLSearchParams.append(value, params[value])
      })
      return URLSearchParams
    },

    getUrl (indexType = 'url') {
      const url = this[indexType]
      if (url === undefined) return url

      const params = this.getStringifyOptions({ indexType })

      const uri = this.$helpers.isFunction(url) ?
        url(this.getDatatableOptions(), params) :
        (!url ?
            (this.$helpers.isFunction(this.url) ? this.url(this.getDatatableOptions(), params) : this.url) :
            url
        )

      const u = uri.split('?')
      const k = `indexType=${indexType}`

      if (!u.length) return uri

      if (u.length > 1) {
        u[u.length - 1].indexOf('indexType') === -1 && (u[u.length - 1] = `${u[u.length - 1]}&${k}`)
      } else
        u.push(k)

      return u.join('?')
    },

    getRequest (method = 'get', url = undefined, indexType = 'url') {
      url = url || this.getUrl(indexType)
      const body = this.getRequestData({ indexType })
      const data = method.toLowerCase() === 'post' ? body : {}
      const params = method.toLowerCase() === 'get' ? body : {}

      // const responseType = indexType === 'url' ? 'json' : 'arraybuffer';
      const responseType = indexType === 'url' ? 'json' : 'blob'

      // console.log(responseType);
      const config = {
        url,
        method,
        data,
        params,
        responseType
      }
      return this.$axios.request(config)
    },

    getRequestParams (params = {}) {
      const options = this.getDatatableOptions()
      const filter = this.getDatatableFilters()
      const pageTitle = this.getPageTitle()
      return { pageTitle, ...options, filter, ...params }
    },

    getRequestData (params = {}) {
      const data = this.getRequestParams()
      data.headerItems = this.getRequestDatatableHeaders
      data.items = this.selectedIds
      return { ...data, ...params }
    },

    fetchData () {
      if (this.loading || !this.getUrl('url')) return
      this.loading = !0
      this.clearSelectedItems()
      this.clearItems()

      return new Promise((resolve, reject) => {
        let action = this.getRequest(this.getFetchMethod)

        if (!action) return resolve(action)

        action.then(response => {
          const { data } = response || {}
          if (data && data.success) {
            this.items = this.$helpers.isOnlyArray(data.data) ? data.data : []
            const { meta } = data
            this.serverItemsLength = meta ? meta.total : 0
          }
          resolve(response)
          return response
        }).catch(error => {
          reject(error)
          return error
        })
        /*
        // console.log(action);
        return;
        this.$helpers.isString(action) && (action = this.doFetch(action));

        action && 'then' in action &&
        action.then(response => {
          const {data} = response || {};
          if(data && data.success) {
            this.items = this.$helpers.isOnlyArray(data.data) ? data.data : [];
            const {meta} = data;
            this.serverItemsLength = meta ? meta.total : 0;
          }
          resolve(response);
          return response;
        })
        .catch(error => {
          reject(error);
          return error;
        })
        || resolve(action);

         */
      }).catch(error => {
        console.error('fetchData', error)
        return error
      }).finally(() => this.loading = !1)
    },

    getExportPromise (method = 'get', url, indexType) {
      return (new Promise((resolve, reject) => {
        let action = this.getRequest(method, url, indexType)
        action.then(response => {
          this.$helpers.downloadFromResponse(response)
          this.clearSelectedItems()
          resolve(response)
          return response
        }).catch(error => {
          reject(error)
          return error
        })
      }))
    },

    exportPdf () {
      const indexType = 'pdf'
      const url = this.getUrl(indexType)
      if (!url || this.loadingPdf) return

      this.loadingPdf = !0

      const promise = () => this.getExportPromise('get', url, indexType).finally(() => this.loadingPdf = !1)

      if (this.selectedIds.length < 1)
        return this.confirm(this.$t('messages.exportAll'), () => promise(), () => this.loadingPdf = !1, 'warning')
      else
        return promise()
    },

    exportExcel () {
      const indexType = 'excel'
      const url = this.getUrl(indexType)
      if (!url || this.loadingExcel) return

      this.loadingExcel = !0

      const promise = () => this.getExportPromise('get', url, indexType).finally(() => this.loadingExcel = !1)

      if (this.selectedIds.length < 1)
        return this.confirm(this.$t('messages.exportAll'), () => promise(), () => this.loadingExcel = !1, 'warning')
      else
        return promise()
    },

    clearSelectedItems () {
      this.selectedItems = undefined
    },

    clearItems () {
      this.items = []
    },

    closeDialog () {
      this.modal = !1
    }
  },
  beforeCreate () {
    this.$on('refresh', (...e) => this.refresh(...e))
  },
  mounted () {
    setTimeout(this.iniDatatable, 250)
    // console.log(this.hideDefaultFooter)
  },
  mixins: [GetHeadersMixin]
}
