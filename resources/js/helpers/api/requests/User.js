

import Auth from '@helpers/auth'
import axios from 'axios'

const publicPrefix = 'User'
const prefix = `Panel/User`
export default {
  sideMenu () {
    return axios.get(`${publicPrefix}/side-menu`)
  },
  profile () {
    return axios.get(`${publicPrefix}/profile`)
  },
  refreshProfile () {
    return this.profile().then((response) => {
      let { data } = response || {}

      if (data && data.data && data.success === true) {
        Auth.save({ user: data.data })

      }
      return response
    })
  },
  updateProfile (form = {}) {
    return axios.post(`${publicPrefix}/profile`, form)
  },
  index (params, config = {}) {
    return params === true ? prefix : axios.get(prefix, { ...config, params })
  },
  store (data = {}, config = {}) {
    return data === true ? prefix : axios.post(prefix, data, config)
  },
  show (id, config = {}) {
    const url = `${prefix}/${id}`
    return id === true ? url : axios.get(url, config)
  },
  update (id, data = {}, config = {}) {
    const url = `${prefix}/${id}`
    return id === true ? url : axios.put(url, data, config)
  },
  destroy (id, config = {}) {
    const url = `${prefix}/${id}`
    return id === true ? url : axios.delete(url, config)
  }
}
