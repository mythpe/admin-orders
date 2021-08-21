
import { trimStart } from 'lodash'

export const API_CONFIG = {
  baseUrl: '/api',
  url (url) {
    return `${this.baseUrl}/${trimStart(url, '/')}`
  }
}

export default API_CONFIG
