
<script>

import AutoSelect from '@base-inputs/AutoSelect'

export default {
  name: 'FilterSelect',
  extends: AutoSelect,
  props: {
    items: {
      default () {
        return this.defItems
      }
    },
    clearable: {
      default () {
        return true
      }
    }
  },
  data () {
    return {}
  },
  computed: {
    defItems () {
      return [
        // {text: this.parseAttribute('none'), value: null},
        { text: this.parseAttribute('yes'), value: 1 },
        { text: this.parseAttribute('no'), value: 0 }
      ]
    }
  },
  methods: {}
}
</script>
