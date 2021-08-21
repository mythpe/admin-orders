
<template>
  <app-container :fluid='fluid'>
    <div class='my-16'></div>
    <v-card
      v-bind='$attrs'
      v-on='$listeners'
      :class='classes'
      class='v-card--app pa-3'
    >
      <div class='d-flex grow flex-wrap card--div'>
        <v-avatar
          v-if='avatar'
          class='mx-auto v-card--app__avatar elevation-6'
          color='grey'
          size='128'
        >
          <v-img :src='avatar' />
        </v-avatar>

        <v-sheet
          v-else
          :class='sheetClasses'
          :color='color'
          :max-height='sheetMaxHeight'
          :width='sheetWidth'
          elevation='6'
        >
          <slot
            v-if='$slots.heading'
            name='heading'
          ></slot>
          <slot
            v-else-if='$slots.image'
            name='image'
          ></slot>
          <div
            v-else-if='title && !icon'
            :class='titleClasses'
          >{{ title }}
          </div>
          <v-icon
            v-else-if='icon'
            size='32'
          >{{ icon }}
          </v-icon>
          <div
            v-if='text'
            class='text-start px-4'
          >{{ text }}
          </div>
        </v-sheet>

        <div
          v-if="$slots['after-heading']"
          class='ml-6'
        >
          <slot name='after-heading'></slot>
        </div>

        <div
          v-else-if='icon && title'
          class='ms-4'
        >
          <div class='text-button font-weight-light'>{{ title }}</div>
        </div>

        <div
          v-if="$slots['after-title'] && title"
          class='ml-6'
        >
          <slot name='after-title'></slot>
        </div>

      </div>
      <slot></slot>
      <template v-if='$slots.actions'>
        <v-divider class='mt-2' />

        <v-card-actions class='pb-0'>
          <slot name='actions' />
        </v-card-actions>
      </template>
    </v-card>
  </app-container>
</template>

<script>
export default {
  name: 'Card',
  props: {
    fluid: {
      type: Boolean,
      default: () => false
    },
    avatar: {
      type: String,
      default: () => ''
    },
    color: {
      type: String,
      default: () => 'secondary'
    },
    icon: {
      type: String,
      default: () => undefined
    },
    text: {
      type: String,
      default: () => ''
    },
    title: {
      type: String,
      default: () => ''
    }
  },

  computed: {
    titleClasses () {
      return ['text-body-1', 'text-sm-h4', 'font-weight-light', 'text-start'].join(' ')
    },
    classes () {
      return {
        'v-card--app--has-heading': this.hasHeading
      }
    },
    hasHeading () {
      return Boolean(this.$slots.heading || this.title || this.icon)
    },
    hasAltHeading () {
      return Boolean(this.$slots.heading || (this.title && this.icon))
    },
    sheetClasses () {
      return [
        'text-center',
        'v-card--app__heading',
        'mb-6',
        !this.$slots.image ? 'pa-7' : ''
      ].join(' ')
    },
    sheetHeadingClasses () {
      return [
        'text-center',
        'v-card--app__heading-2',
        'py-2',
        'px-10'
      ].join(' ')
    },
    sheetWidth () {
      return this.icon ? 'auto' : '100%'
    },
    sheetMaxHeight () {
      return this.icon ? 90 : undefined
    }
  }
}
</script>

<style
  lang='sass'
  scoped
>
.v-card--app
  &__avatar
    position: relative
    top: -64px
    margin-bottom: -32px

  &__heading
    position: relative
    top: -40px
    transition: .3s ease
    z-index: 1

  &__heading-2
    position: relative
    top: -15px
    transition: .3s ease
    z-index: 1

.v-sheet
  border-radius: 4px
</style>
