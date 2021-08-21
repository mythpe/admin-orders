
<template>
  <div class='pt-2'>
    <app-form
      ref='form'
      v-slot='v'
      :errors='errors'
      :submit='submit'
    >
      <app-row>
        <app-text-input
          v-model='formData.name'
          name='name'
          rules='required'
          md="6"
        />
        <app-text-input
          v-model='formData.username'
          name='username'
          rules='required'
          md="6"
        />
        <app-roles
          v-model="formData.role_id"
          required
          md="6"
          name="role_id"
        />
        <app-permissions
          v-model="formData.permissions"
          clearable
          multiple
          md="6"
          name="permissions"
        />
        <app-auto-select
          v-model="formData.positive_color"
          md="6"
          name="positive_color"
          :items='userColorsSelect'
        />
        <app-auto-select
          v-model="formData.negative_color"
          md="6"
          name="negative_color"
          :items='userColorsSelect'
        />
      </app-row>

      <app-row>
        <app-password-input
          v-model='formData.password'
          autocomplete='new-password'
          name='password'
          rules=''
          sm='6'
        />
        <app-password-input
          v-model='formData.password_confirmation'
          name='password_confirmation'
          rules='confirmed:password'
          sm='6'
        />
      </app-row>

      <!--      <app-row>-->
      <!--        <app-switcher-->
      <!--            v-model="formData.active"-->
      <!--            md="2"-->
      <!--            name="active"-->
      <!--            sm="6"-->
      <!--        />-->
      <!--      </app-row>-->

      <app-row>
        <v-col>
          <app-submit :loading='formLoading'>{{ $t(btnText) }}</app-submit>
        </v-col>
      </app-row>
    </app-form>
  </div>
</template>
<script>

export default {
  name: 'UserForm',
  props: {
    btnText: {
      type: String,
      default: () => 'save'
    },

    form: {
      type: Object,
      required: !0,
      default: () => ({})
    },

    loading: {
      type: Boolean,
      default: () => !1
    },

    errors: {
      type: Object,
      default: () => ({})
    }
  },
  data () {
    return {
      formData: {},
      formErrors: {},
      formLoading: !1
    }
  },
  watch: {
    formData (v) {
      this.$emit('update:form', v)
    },
    formLoading (v) {
      this.$emit('update:loading', v)
    },
    formErrors (v) {
      this.$emit('update:errors', v)
    },

    form (v) {
      this.formData = v
    },
    loading (v) {
      this.formLoading = v
    },
    errors (v) {
      this.formErrors = v
    }
  },
  methods: {
    submit (v) {
      this.$emit('submit', v)
    },
    getForm () {
      return this.$refs.form
    },
    reset () {
      this.getForm() && this.getForm().reset()
    },

    setErrors (errors = {}) {
      this.getForm() && this.getForm().setErrors(errors)
    },

    iniComponent () {
      this.formData = this.form
      this.formLoading = this.loading
      this.formErrors = this.errors
      this.$refs.form && this.$refs.form.reset()
    }
  },
  mounted () {
    this.iniComponent()
  }
}
</script>
