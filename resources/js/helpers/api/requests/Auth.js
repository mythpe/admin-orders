

import axios from 'axios'

export default {
  login: post => {
    return axios.post('login', post)
  },
  logout () {
    return axios.get('logout')
  },
  checkToken () {
    return axios.get('check-token')
  }
}
