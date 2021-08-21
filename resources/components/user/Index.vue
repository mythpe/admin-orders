<template>
  <app-card
    :title='getPageTitle()'
    class='px-5 py-3'
    icon='mdi-clipboard-text'
  >
    <app-datatable
      ref='datatable'
      :formDialog.sync='dialog'
      :formDialogLoading.sync='formDialogLoading'
      :headers='headers'
      :modalTitle='datatableDialogTitle'
      :url='tableUrl'
      center
      excel
      pdf
      search
    >
      <template #filter='{datatableFilters}'>
        <app-row dense>
          <app-roles
            v-model='datatableFilters.role_id'
            clearable
            md='6'
            multiple
            name='role_id'
          />
        </app-row>
      </template>

      <template #formDialog='props'>
        <user-form
          ref='form'
          v-on='props.on'
          :errors.sync='formErrors'
          :form.sync='selectedItem'
          :loading.sync='formDialogLoading'
          @submit='submitForm'
        />
      </template>

      <template v-slot:item.name='{item,on}'>
        <app-datatable-edit-dialog
          v-model='item.name'
          v-on='on'
          :item.sync='item'
          :update-method='updateOneItemData'
          name='name'
        />
      </template>
      <template v-slot:item.username='{item,on}'>
        <app-datatable-edit-dialog
          v-model='item.username'
          v-on='on'
          :item.sync='item'
          :update-method='updateOneItemData'
          name='username'
        />
      </template>
      <template #item.control='{item,on}'>
        <app-dt-btn
          update
          @click='showEditDialog(item)'
        />
        <app-dt-btn
          v-if='item.is_admin !== !0'
          destroy
          @click='deleteItem(item)'
        />
      </template>
    </app-datatable>
  </app-card>
</template>
<script>
import DatatableMixin from '@mixins/DatatableMixin'
import MetaInfoMixin from '@mixins/MetaInfoMixin'
import UserForm from './UserForm'

export default {
  name: 'Index',
  components: { UserForm },
  helperApiName: 'user',
  mixins: [MetaInfoMixin, DatatableMixin],
  data () {
    return {
      headers: ['name', 'username', 'role_id_to_string', 'permissions_to_string', 'control']
    }
  }
}
</script>
