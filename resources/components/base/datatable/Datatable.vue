
<template>
  <app-container>
    <v-data-table
      ref='datatable'
      v-model='selectedItems'
      v-bind='$attrs'
      v-on='datatableListeners'
      :calculate-widths='calculateWidths'
      :dense='dense'
      :fixed-header='fixedHeader'
      :footer-props='footer'
      :headers='getHeaders'
      :height='height'
      :item-key='itemKey'
      :items='items'
      :items-per-page='datatableOptions.itemsPerPage'
      :loader-height='loaderHeight'
      :loading.sync='loading'
      :maxHeight='maxHeight'
      :minHeight='minHeight'
      :multi-sort='multiSort'
      :options.sync='datatableOptions'
      :search='datatableOptions.search'
      :server-items-length='serverItemsLength'
      :show-select='getShowSelect'
      :single-select='singleSelect'
      :url='computedUrlDatatable'
      @page-count='pageCount = $event'
    >

      <template
        v-if='hasTopSlot'
        #top
      >
        <slot name='top'></slot>
        <v-expansion-panels
          v-model='computedExpansionTopSlot'
          class='mb-4'
          focusable
          hover
          popout
        >
          <v-expansion-panel>
            <v-expansion-panel-header>{{ $t('expansionPanelHeaderOptions') }}</v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-container
                class='pa-0 pt-5'
                fluid
              >
                <v-responsive
                  v-if='hasFilterSlot'
                  class='overflow-y-auto elevation-1'
                  max-height='250'
                >
                  <v-lazy
                    v-model='expansionLazy'
                    :options='{threshold: .5}'
                    transition='fade-transition'
                  >
                    <v-container
                      class='py-0'
                      fluid
                    >
                      <slot
                        v-bind='slotBind'
                        name='filter'
                      ></slot>
                    </v-container>
                  </v-lazy>
                </v-responsive>
                <app-row dense>
                  <v-col
                    v-if='search'
                    cols='12'
                    order='2'
                    order-sm='1'
                    sm='8'
                  >
                    <app-text-input
                      v-model='datatableOptions.search'
                      all='12'
                      append-icon='mdi-magnify'
                      name='search'
                    />
                  </v-col>
                  <v-col
                    align-self='center'
                    class='text-start text-sm-end mb-4 mb-sm-0'
                    order='1'
                    order-sm='2'
                  >
                    <!--<template v-if='canPdf'>-->
                    <!--  <app-tooltip :text="parseAttribute('exportPdf')">-->
                    <!--    <app-btn-->
                    <!--      :loading='loadingPdf'-->
                    <!--      color='error'-->
                    <!--      max-width='auto'-->
                    <!--      min-width='auto'-->
                    <!--      @click='exportPdf'-->
                    <!--    >-->
                    <!--      <v-icon>insert_drive_file</v-icon>-->
                    <!--      {{ getSelectedLength }}-->
                    <!--    </app-btn>-->
                    <!--  </app-tooltip>-->
                    <!--</template>-->

                    <!--<template v-if='canExcel'>-->
                    <!--  <app-tooltip :text="parseAttribute('exportExcel')">-->
                    <!--    <app-btn-->
                    <!--      :loading='loadingExcel'-->
                    <!--      color='success'-->
                    <!--      max-width='auto'-->
                    <!--      min-width='auto'-->
                    <!--      @click='exportExcel'-->
                    <!--    >-->
                    <!--      <v-icon>insert_drive_file</v-icon>-->
                    <!--      {{ getSelectedLength }}-->
                    <!--    </app-btn>-->
                    <!--  </app-tooltip>-->
                    <!--</template>-->

                    <app-tooltip :text="parseAttribute('refreshTable')">
                      <app-btn
                        :loading='loading'
                        color='accent'
                        max-width='auto'
                        min-width='auto'
                        @click='refresh'
                      >
                        <v-icon>refresh</v-icon>
                      </app-btn>
                    </app-tooltip>

                    <template v-if='hasBtnDialog'>
                      <app-tooltip :text='getModalBtnTooltip'>
                        <app-btn
                          max-width='auto'
                          min-width='auto'
                          @click='clickModalActivator'
                        >
                          <v-icon v-if='getModalBtnIcon'>{{ getModalBtnIcon }}</v-icon>
                        </app-btn>
                      </app-tooltip>
                    </template>
                  </v-col>
                </app-row>
              </v-container>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </template>

      <template
        v-for='slot in getScopedSlots'
        v-slot:[slot]='props'
      >
        <slot
          v-bind='{...slotBind,... props,}'
          :name='slot'
        ></slot>
      </template>
    </v-data-table>

    <v-dialog
      v-if="$scopedSlots['formDialog']"
      v-model='modal'
      :fullscreen='getFullscreen'
      max-width='950'
      persistent
      scrollable
    >
      <v-card
        :loading='formDialogLoading'
        flat
        loader-height='10'
        tile
      >
        <v-toolbar
          class='justify-content-center'
          color='secondary'
          max-height='60'
        >
          <v-toolbar-title v-text='getModalTitle' />
        </v-toolbar>
        <v-card-text>
          <slot
            v-bind='slotBind'
            name='formDialog'
          ></slot>
        </v-card-text>
        <v-divider />
        <v-card-actions>
          <v-spacer></v-spacer>
          <app-btn
            :loading='formDialogLoading'
            color='error'
            min-width='120'
            tooltip='close_window'
            @click='closeDialog'
          >
            {{ $t('close') }}
          </app-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </app-container>
</template>

<script src='./DatatableScript.js'></script>
