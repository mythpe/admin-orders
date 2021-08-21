
<script>
import NumberInput from '@base-inputs/NumberInput'

export default {
  name: 'PriceInput',
  extends: NumberInput,
  props: {
    format: {
      type: Boolean,
      default: () => true
    },
    minus: {
      type: Boolean,
      default: () => true
    }
  }
}
</script>
