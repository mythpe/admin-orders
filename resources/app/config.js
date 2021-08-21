

import api from '@/app/api-config'
import routes from '@routes/config'

export const defaultLocale = 'en'
export const rtl = true
export const APP_DARK = true
export const LOCALE_STORAGE_KEY = 'locale'
export const THEME_DARK_STORAGE = 'theme_dark'

const APP_CONFIG = {
  api,
  routes
}

export default APP_CONFIG
