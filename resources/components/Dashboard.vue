<template>
  <v-container>
    <v-row v-if='isAdmin'>
      <v-col cols='12'>
        <v-simple-table>
          <template>
            <thead>
            <tr>
              <th class='text-center'>START</th>
              <th class='text-center'>OPEN</th>
              <th class='text-center'>CLOSE</th>
              <th class='text-center'>TOTAL</th>
              <th class='text-center'>SUM</th>
              <th class='text-center'>SUB</th>
            </tr>
            </thead>
            <tbody>
            <tr class='text-center'>
              <td>{{ $helpers.toNumberFormat(settingStart) }}</td>
              <td>{{ $helpers.toNumberFormat(absOpenTotal) }}</td>
              <td>{{ $helpers.toNumberFormat(absCloseTotal) }}</td>
              <td>{{ $helpers.toNumberFormat(allTotal) }}</td>
              <td>{{ $helpers.toNumberFormat(totalSum) }}</td>
              <td>{{ $helpers.toNumberFormat(totalSub) }}</td>
            </tr>
            <tr class='text-center'>
              <td></td>
              <td>{{ openItems.length }}</td>
              <td>{{ closeItems.length }}</td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-col>
    </v-row>
    <v-row dense>
      <v-col md='4'>
        <v-data-table
          v-if='hasOpenPermission'
          v-bind='tableAttrs'
          :headers='openHeaders'
          :item-class='openItemClass'
          :items='getOpenItems'
          disable-sort
          v-on:click:row='openItemClick'
        >
          <template #footer>
            <v-container>
              <v-row>
                <v-col cols='auto'>
                  <h3 class='error--text'>Total: {{ openTotal }}</h3>
                </v-col>
              </v-row>
            </v-container>
          </template>
        </v-data-table>
      </v-col>
      <v-col md='4'>
        <v-data-table
          v-if='hasClosePermission'
          v-bind='tableAttrs'
          :headers='closeHeaders'
          :item-class='closeItemClass'
          :items='getCloseItems'
        >
          <template #footer>
            <v-container>
              <v-row>
                <v-col cols='auto'>
                  <h3 class='error--text'>Total: {{ closeTotal }}</h3>
                </v-col>
              </v-row>
            </v-container>
          </template>
        </v-data-table>
      </v-col>
      <v-col md='4'>
        <v-data-table
          v-if='hasRankPermission'
          v-bind='tableAttrs'
          :headers='rankHeaders'
          :item-class='rankItemClass'
          :items='getRankItems'
        >
          <template #footer>
            <v-container>
              <v-row>
                <v-col cols='auto'>
                  <h3 class='error--text'>Total: {{ rankTotal }}</h3>
                </v-col>
              </v-row>
            </v-container>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>

import MetaInfoMixin from '@mixins/MetaInfoMixin'
import GetHeadersMixin from '@mixins/GetHeadersMixin'
import _ from 'lodash'

export default {
  name: 'Dashboard',
  mixins: [MetaInfoMixin, GetHeadersMixin],
  data () {
    return {
      center: !0,
      settingStart: 0,
      loading: !1,
      creating: !1,
      startNumbers: -1000,
      endNumbers: 1000,
      openInterval: null,
      tableAttrs: {
        'hide-default-footer': !0,
        'disable-pagination': !0,
        'disable-filtering': !0,
        'fixed-header': !0,
        'no-data-text': '',
        'height': 500
      },
      openItems: [],
      rankItems: [],
      closeItems: []
    }
  },
  methods: {
    hasPermission (name) {
      if (!this.authUser || !name) return false
      if (this.authUser.is_admin === !0) return !0

      name = name.toLowerCase();
      const p = this.authUser.permissions_to_string.toLowerCase().split(',')
      return p.indexOf(name) >= 0
    },
    getSettings () {
      this.$api.methods.setting.get().then(({ _data }) => {
        this.settingStart = parseInt(_data?.start) || 0
      }).catch(e => e)
    },
    getOpenNumber () {
      let num = 0

      do {
        num = _.random(this.startNumbers, this.endNumbers, !1)
      }
      while (
        this.closeItems.find(e => parseInt(e.total) === num) !== undefined &&
        !this.openItems.find(e => parseInt(e.total) === num)
        )

      return num
    },
    setItemsData (data) {
      this.closeItems = data?.orders || []
      this.rankItems = data?.ranks || []
      if(data?.user)
      this.$root.updateProfile(data.user)
    },
    makeOrder (data) {
      if (this.creating) return
      this.creating = !0
      axios.post('Panel/Order/makeOrder', data).then(({ _data }) => this.setItemsData(_data)).catch(e => e).finally(() => this.creating = !1)
    },
    fetchData () {
      if (this.loading) return
      this.loading = !0
      axios.get('Panel/Order/getOrders').then(({ _data }) => this.setItemsData(_data)).catch(e => e).finally(() => this.loading = !1)
    },
    iniInterval () {
      this.resetOpenData()
      this.fetchData()
      if (!this.openInterval) {
        this.openInterval = setInterval(() => {
          this.resetOpenData()
          this.fetchData()
          // this.$root.updateProfile()
        }, 5 * 1000)
      }
    },
    resetOpenData () {
      this.openItems = []
      for (let i = 0; i <= 10; i++) {
        this.openItems = [...this.openItems, {
          total: this.getOpenNumber()
        }]
      }
    },

    openItemClass (item) {

      let c = ['pointer']
      const value = item.total

      // if (value >= -100 && value <= 100) {
      if (value > 0) {
        c.push(this.authUser?.positive_color || 'green')
      } else if (value < 0) {
        c.push(this.authUser?.negative_color || 'red')
      }
      // }

      return c.join(' ')
    },
    openItemClick (item) {
      this.confirm(`Are you sure to close ${item.total}?`, () => this.makeOrder({ total: item.total }))
    },

    closeItemClass (item) {
      let c = []
      if (item.close > 5 && item.close < 10) {
        // c.push(' ')
      }
      return c.join(' ')
    },

    rankItemClass (item) {
      let c = []
      if (item.close > 5 && item.close < 10) {
        c.push(' ')
      }
      return c.join(' ')
    }
  },
  mounted () {
    this.getSettings()
    this.iniInterval()
  },
  beforeDestroy () {
    if (this.openInterval) {
      clearInterval(this.openInterval)
    }
  },
  computed: {
    hasOpenPermission () {
      return this.hasPermission('open')
    },
    hasClosePermission () {
      return this.hasPermission('close')
    },
    hasRankPermission () {
      return this.hasPermission('rank')
    },
    openHeaders () {
      return this.parseHeaders([
        { value: 'total', text: 'open' }
      ])
    },
    getOpenItems () {
      return this.openItems.map(e => ({ ...e, key: `open-${e.key}` }))
    },
    openTotal () {
      let total = 0
      this.openItems.map(e => total += parseFloat(e.total) || 0)
      return total
    },

    closeHeaders () {
      return this.parseHeaders([
        'close',
        'username'
      ])
    },
    getCloseItems () {
      return this.closeItems.map(e => ({ ...e, key: `close-${e.key}` }))
    },
    closeTotal () {
      let total = 0
      this.closeItems.map(e => total += parseFloat(e.total) || 0)
      return total
    },

    rankHeaders () {
      return [
        {
          text: 'Rank',
          value: 'username'
        },
        {
          text: '$$$',
          value: 'total'
        }
      ]
    },
    getRankItems () {
      return this.rankItems.map(e => ({ ...e, key: `rank-${e.key}` }))
    },
    rankTotal () {
      let total = 0
      this.rankItems.map(e => total += parseFloat(e.total) || 0)
      return total
    },

    absOpenTotal () {
      return Math.abs(this.openTotal)
    },
    absCloseTotal () {
      return Math.abs(this.closeTotal)
    },

    allTotal () {
      // return Math.abs(this.openTotal) + Math.abs(this.closeTotal)
      return this.openTotal + this.closeTotal
    },
    totalSum () {
      return this.settingStart + this.allTotal
    },
    totalSub () {
      return this.settingStart - this.allTotal
    }
  }
}
</script>
<style>
.main-row {
    height: 80vh;
}
</style>
