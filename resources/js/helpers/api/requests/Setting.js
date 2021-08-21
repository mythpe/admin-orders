import axios from 'axios'

const prefix = `Panel/Setting`
export default {
  get: () => axios.get(prefix),
  store: form => axios.post(prefix, form)
}
