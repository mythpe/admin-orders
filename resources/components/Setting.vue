<template>
  <v-container>
    <v-toolbar
      class='mb-3'
      color='primary'
    >
      <v-toolbar-title>{{ parseAttribute('setting') }}</v-toolbar-title>
    </v-toolbar>
    <v-skeleton-loader type='card@1, button@1' v-if='loading'/>
    <v-card v-else>
      <app-form
        :errors='errors'
        :submit='submit'
      >
        <v-container fluid>
          <app-row>
            <app-number-input
              v-model.number='form.start'
              md='6'
              name='start'
              required
            />
            <app-number-input
              v-model.number='form.rst'
              md='6'
              name='rst'
              required
            />
            <app-number-input
              v-model.number='form.rst_plus'
              md='6'
              name='rst_plus'
              required
            />
            <app-number-input
              v-model.number='form.rst_minus'
              md='6'
              name='rst_minus'
              required
            />
          </app-row>
        </v-container>
        <v-card-actions>
          <app-submit :loading='loading'>{{ $t('save') }}</app-submit>
        </v-card-actions>
      </app-form>
    </v-card>
  </v-container>
</template>

<script>

import MetaInfoMixin from '@mixins/MetaInfoMixin'

export default {
  name: 'Setting',
  mixins: [MetaInfoMixin],
  data () {
    return {
      loading: !1,
      errors: {},
      form: {
        start: 0,
        rst: 0,
        rst_plus: 0,
        rst_minus: 0
      }
    }
  },
  mounted () {
    this.iniSetting()
  },
  methods: {
    iniSetting () {
      this.$api.methods.setting.get().then(({ _data }) => {
        // console.log(_data)
        this.form = _data || {}
        // if(data) {
        //   this.form = data.data;
        // }
      })
    },
    submit () {
      if (this.loading) return
      this.loading = !0
      this.errors = {}

      this.$api.methods.setting.store(this.form).then(({ _data}) => {
        this.form = _data || {}
      }).catch(({ _errors }) => {
        this.errors = _errors || {}
      }).finally(() => this.loading = !1)
    }
  }
}
</script>
