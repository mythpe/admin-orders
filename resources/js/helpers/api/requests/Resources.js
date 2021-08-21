

import axios from 'axios'

const prefix = 'Resources'
const Resource = a => `${prefix}/${a}`

export default {
  roles (params = {}, config = {}) {
    const url = Resource('Role')
    return params === true ? url : axios.get(url, { ...config, params: { ...params, itemsPerPage: -1 } })
  },
  permissions (params = {}, config = {}) {
    const url = Resource('Permission')
    return params === true ? url : axios.get(url, { ...config, params: { ...params, itemsPerPage: -1 } })
  },
}
