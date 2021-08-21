
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

      <!--      <template #filter="{datatableFilters}">-->
      <!--        <app-row>-->
      <!--          <app-filter-select-->
      <!--            v-model="datatableFilters.property_completed"-->
      <!--            clearable-->
      <!--            md="6"-->
      <!--            name="property_completed"-->
      <!--          />-->
      <!--          <app-filter-select-->
      <!--            v-model="datatableFilters.property_uncompleted"-->
      <!--            clearable-->
      <!--            md="6"-->
      <!--            name="property_uncompleted"-->
      <!--          />-->
      <!--          <app-filter-select-->
      <!--            v-model="datatableFilters.joint"-->
      <!--            clearable-->
      <!--            md="6"-->
      <!--            name="joint"-->
      <!--          />-->
      <!--          <app-filter-select-->
      <!--            v-model="datatableFilters.guarantees"-->
      <!--            clearable-->
      <!--            md="6"-->
      <!--            name="guarantees"-->
      <!--          />-->
      <!--        </app-row>-->
      <!--      </template>-->

      <template #formDialog='props'>
        <div class='pt-2'>
          <app-form
            ref='form'
            v-slot='v'
            :errors='formErrors'
            :submit='submitForm'
          >
            <app-row>
              <app-text-input
                v-model='selectedItem.name_ar'
                autofocus
                name='name_ar'
                rules='required'
                sm='6'
              />
              <app-text-input
                v-model='selectedItem.name_en'
                name='name_en'
                rules='required'
                sm='6'
              />
              <app-text-input
                v-model='selectedItem.code'
                name='code'
                rules='required'
                sm='6'
              />
              <app-number-input
                v-model='selectedItem.sort_order'
                minus
                name='sort_order'
                sm='6'
              />
            </app-row>
            <v-divider></v-divider>
            <app-row>
              <app-switcher
                v-model='selectedItem.property_completed'
                name='property_completed'
                sm='4'
              />
              <app-switcher
                v-model='selectedItem.property_uncompleted'
                name='property_uncompleted'
                sm='4'
              />
              <app-switcher
                v-model='selectedItem.joint'
                name='joint'
                sm='4'
              />
              <app-switcher
                v-model='selectedItem.quest_check'
                name='quest_check'
                sm='4'
              />
              <app-switcher
                v-model='selectedItem.bear_tax'
                name='bear_tax'
                sm='4'
              />
              <app-switcher
                v-model='selectedItem.guarantees'
                name='guarantees'
                sm='4'
              />
            </app-row>
            <v-divider></v-divider>
            <app-row>
              <app-switcher
                v-model='selectedItem.shl'
                name='shl'
                sm='4'
              />
              <app-switcher
                v-model='selectedItem.active'
                name='active'
                sm='4'
              />
            </app-row>
            <v-divider></v-divider>
            <app-row>
              <v-col>
                <app-submit :loading='formDialogLoading'>{{ $t('save') }}</app-submit>
              </v-col>
            </app-row>
          </app-form>
        </div>
      </template>
      <template #item.control='{item}'>
        <app-dt-btn
          update
          @click='showEditDialog(item)'
        />
        <app-dt-btn
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

export default {
  name: 'Index',
  helperApiName: 'bank',
  mixins: [MetaInfoMixin, DatatableMixin],
  data () {
    return {
      headers: [
        'name',
        'property_completed_to_string',
        'property_uncompleted_to_string',
        'joint_to_string',
        'guarantees_to_string',
        'quest_check_to_string',
        'bear_tax_to_string',
        'shl_to_string',
        'active_to_string',
        'control'
      ]
    }
  }
}
</script>
