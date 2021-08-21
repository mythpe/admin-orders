
<script>
import AxiosSelect from '@base-inputs/AxiosSelect'
import Api from '@helpers/api'

export default {
  name: 'Roles',
  extends: AxiosSelect,
  props: {
    clearable: {
      default: () => false
    },
    value: {},
    axiosUrl: {
      default: () => Api.methods.resources.roles
    }
  }
}
</script>
