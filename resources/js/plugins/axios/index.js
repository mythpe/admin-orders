

import API_CONFIG from '@/app/api-config'
import { defaultLocale } from '@/app/config'
import Auth from '@helpers/auth'
import routeAllies from '@routes/config'
import axios from 'axios'
import Qs from 'qs'
import Vue from 'vue'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.baseURL = API_CONFIG.baseUrl
axios.defaults.headers.common['Locale'] = defaultLocale
axios.defaults.headers.common['App-Name'] = process.env.MIX_APP_NAME
axios.defaults.headers.common['App-Version'] = process.env.MIX_APP_VERSION

let token
if ((token = document.head.querySelector('meta[name=\'csrf-token\']'))) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

let accessToken
if ((accessToken = Auth.getAccessToken())) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${accessToken}`
}
axios.defaults.paramsSerializer = params => Qs.stringify(params, { arrayFormat: 'indices' })

// console.log(axios.defaults);

// axios.interceptors.response.use(function (response) {
//   const { data } = response || {}
//
//   if (data) {
//     response.result = data.data
//     if (data.message) {
//       response.resultMessage = data.message
//     }
//   }
//   return response
// }, function (error) {
//
//   const { response } = error || {}
//   const status = response && response.status || null
//   let url
//
//   if (status === 401) {
//     if (window.location.pathname !== (url = routeAllies.auth.logout.path)) {
//       window.location.href = url
//     }
//   }
//   if (status === 403) {
//     if (window.location.pathname !== '/' && window.location.pathname !== '') {
//       window.location.href = '/'
//     }
//   }
//   if (response && response.data) {
//     error.responseError = response.data
//   }
//   return Promise.reject(error)
// })

export const onFulfilled = function (response) {
  if (response) {
    response._data = response?.data?.data
    response._message = response?.data?.message
    response._meta = response?.data?.meta
    response._success = response?.data?.success || false
  }
  return response
}
export const onRejected = function (error) {
  const status = error?.response?.status
  // Not authorized
  if (status === 401) {
    // console.log(123)
    let logout = 'logout'
    if (window.location.pathname !== logout && window.location.pathname !== '/' + logout) {
      window.location.href = '/' + logout
    }
  }

  // No permissions
  if (status === 403) {
    // console.log(12)
    // if (window.location.pathname !== '/' && window.location.pathname !== '') {
    // window.location.href = '/'
    // }
  }

  if (error) {
    error._data = error?.response?.data?.data
    error._message = error?.response?.data?.message
    error._errors = error?.response?.data?.errors || {}
  }
  return Promise.reject(error)
}

axios.interceptors.response.use(onFulfilled, onRejected)

Vue.prototype.$axios = axios
window.axios = axios

export default axios
