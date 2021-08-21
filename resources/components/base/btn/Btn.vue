
<template>
  <app-tooltip
    :disabled='tooltipDisabled'
    :text='getTooltip'
  >
    <BaseBtn
      v-bind='$attrs'
      v-on='$listeners'
    >
      <slot></slot>
    </BaseBtn>
  </app-tooltip>
  <!--  <v-tooltip-->
  <!--      :disabled="tooltipDisabled"-->
  <!--      top-->
  <!--  >-->
  <!--    <template v-slot:activator="{ on, attrs }">-->
  <!--    <i-->
  <!--        v-bind="attrs"-->
  <!--        v-on="on"-->
  <!--    >-->
  <!--    </i>-->
  <!--    </template>-->
  <!--    <span v-if="getTooltip">{{ getTooltip }}</span>-->
  <!--  </v-tooltip>-->
</template>

<script>
import BaseBtn from '@components/base/btn/BaseBtn'

export default {
  name: 'Btn',
  components: { BaseBtn },
  props: {
    tooltip: {
      type: String,
      default: () => undefined
    }
  },
  computed: {
    getTooltip () {
      if (this.tooltip) return this.parseAttribute(this.tooltip)
      return null
    },
    tooltipDisabled () {
      return this.getTooltip === null || this.getTooltip === undefined
    }
  }
}
</script>
