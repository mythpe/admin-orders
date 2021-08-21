

// import moment from 'moment'
import moment from 'moment-hijri'
import Vue from 'vue'
import { defaultLocale } from '@/app/config'

Vue.prototype.$moment = moment
moment.locale(defaultLocale)

export default moment
