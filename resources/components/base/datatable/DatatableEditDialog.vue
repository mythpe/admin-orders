
<template>
  <v-edit-dialog
    ref='editDialog'
    v-bind='$attrs'
    v-on='$listeners'
    :cancel-text='getCancelText'
    :large='large'
    :persistent='persistent'
    :return-value.sync='inputValue'
    :save-text='getSaveText'
    @cancel='cancel'
    @close='close'
    @open='open'
    @save='save'
  > {{ value }}
    <template v-slot:input>
      <template v-if='haveSlot'>
        <slot name='input'></slot>
      </template>
      <template v-else>
        <app-container>
          <app-row danse>
            <component
              :is='componentName'
              v-model='inputValue'
              v-bind='$attrs'
              :errors='componentErrors'
              :name='name'
              all='12'
            />
          </app-row>
        </app-container>
      </template>
      <slot v-bind='{errors}'></slot>
    </template>
  </v-edit-dialog>
</template>

<script src='./DatatableEditDialogScript.js'></script>
