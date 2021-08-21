
<script>
import NumberInput from '@base-inputs/NumberInput'

export default {
  name: 'FloatInput',
  extends: NumberInput,
  props: {
    escapeInput: {
      default: () => (['.'])
    }
  }
}
</script>
