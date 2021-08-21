
<template>
  <app-auto-select
    v-bind='$attrs'
    v-on='$listeners'
    :chips='getChips'
    :clearable='clearable'
    :deletable-chips='getDeletableChips'
    :hide-no-data='getHideNoData'
    :hide-selected='!showSelected'
    :items='items'
    :lazy-model.sync='isActive'
    :loading='loading'
    :multiple='multiple'
    :small-chips='getSmallChips'
    :value='value'
    :vid='vid'
  >
    <slot></slot>
  </app-auto-select>
</template>

<script>

export default {
  name: 'AxiosSelect',
  props: {
    value: {},
    rules: {},
    vid: {},
    smallChips: {
      type: Boolean,
      default: () => undefined
    },
    hideNoData: {
      type: Boolean,
      default: () => undefined
    },
    deletableChips: {
      type: Boolean,
      default: () => undefined
    },
    chips: {
      type: Boolean,
      default: () => undefined
    },
    multiple: {
      type: Boolean,
      default: () => !1
    },
    showSelected: {
      type: Boolean,
      default: () => false
    },
    clearable: {
      default: () => false
    },
    axiosUrl: {
      type: [String, Function],
      required: !0
    },
    axiosMethod: {
      type: String,
      default: () => 'get'
    },
    axiosData: {},
    axiosParams: {},
    axiosConfig: {
      type: Object,
      default: () => ({})
    }
  },
  data () {
    return {
      isActive: false,
      loading: !1,
      items: [],
      fetched: false
    }
  },
  computed: {
    getAxiosUrl () {
      return this.axiosUrl
    },
    getDeletableChips () {
      return this.deletableChips === undefined ? this.multiple : this.deletableChips
    },
    getSmallChips () {
      return this.smallChips === undefined ? this.chips : this.smallChips
    },
    getChips () {
      return this.chips === undefined ? this.multiple : this.chips
    },
    getHideNoData () {
      return this.hideNoData
      return this.hideNoData === undefined ? this.multiple : this.hideNoData
    }
  },
  methods: {
    setLoading (v) {
      this.loading = v
    },
    setItems (v) {
      this.items = v
    },
    getRequest (url) {
      return this.$axios.request({
        url,
        method: this.axiosMethod,
        data: this.axiosData,
        params: this.axiosParams,
        ...this.axiosConfig
      })
    },
    fetchData () {
      if (this.loading || this.fetched) return
      const items = this.getAxiosItems()
      this.setLoading(!0)

      if (items !== undefined && items !== null) {
        this.complete(items)
        this.finally()
        return
      }

      // this.setItems([]);

      const func = () => {
        let axios = this.$helpers.isFunction(this.getAxiosUrl) ? this.getAxiosUrl() : this.getAxiosUrl
        this.$helpers.isString(axios) && (axios = this.getRequest(axios))

        this.$nextTick(() => axios.then((request) => {
          const { data } = request || {}
          if (data && data.data && this.$helpers.isOnlyArray(data.data)) {
            this.complete(data.data)
          }
        }).finally(this.finally))
      }
      this.queueAxios(func)
    },
    complete (data) {
      this.setItems(data)
      this.setIniAxios(this.getAxiosName(), data)
      this.fetched = !0
    },
    finally () {
      this.setLoading(!1)
    }
  },
  watch: {
    items (v) {
      this.$emit('set-items', v)
    }
    // isActive(v) {
    //   // if(v === true) {
    //   //   this.$nextTick(() => this.fetchData());
    //   // }
    //   // console.log(v);
    // }
  },
  mounted () {
    // console.log(this.$options.name)
    this.$nextTick(() => this.fetchData())
  }
}
</script>
