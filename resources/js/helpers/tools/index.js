

import _ from 'lodash'
import moment from 'moment-hijri'
import qs from 'qs'

import goTo from 'vuetify/es5/services/goto'

export const Tools = {
  goTo (target = 0, GoToOptions = {}) {
    GoToOptions = {
      easing: 'linear',
      duration: 500,
      ...GoToOptions
    }
    return goTo(target, GoToOptions)
  },
  isString: (...value) => _.isString(...value),
  isBoolean: (...value) => _.isBoolean(...value),
  isFunction: (...value) => _.isFunction(...value),
  isArray: (...value) => _.isArray(value) || _.isObject(value),
  isOnlyArray: (...value) => _.isArray(...value),
  isOnlyObject: (...value) => _.isObject(...value),
  isNumeric: v => !isNaN(parseFloat(v)) && isFinite(v) && v.constructor !== Array,

  isKsaMobile: mobile => {
    mobile = Tools.fromArabicNumber(mobile) || ''
    if (!mobile) return !1
    const c1 = mobile.substr(0, 2)
    const c2 = parseInt(mobile.substr(0, 1))
    return mobile &&
      ((mobile.length === 10 && (c1 === '05' || parseInt(c1) === 5)) || (mobile.length === 9 && c2 === 5))
  },
  findBy (search, value, column = 'id') {
    return _.find(search, e => this.isArray(e) ? e[column] === value : e === value)
  },
  queryStringify: v => new URLSearchParams(qs.stringify(v, {
    arrayFormat: 'indices'
    // encodeValuesOnly: true,
    // encode: false,
  })),
  downloadFromResponse (response) {
    if (!response) return

    let name = response.headers['content-disposition'] || ''
    name = name.split('filename=').pop().replace(/^\"+|\"+$/g, '')
    if (!name) return

    var fileURL = window.URL.createObjectURL(new Blob([response.data]))
    var fileLink = document.createElement('a')
    if (!fileLink || !fileURL) return

    fileLink.href = fileURL
    fileLink.setAttribute('download', name)
    document.body.appendChild(fileLink)
    fileLink.click()
    setTimeout(() => {
      document.body.removeChild(fileLink)
      URL.revokeObjectURL(fileURL)
    }, 3000)
  },

  fromArabicNumber (n) {
    if (!n) return n
    n = '' + n
    n = n.replace(/٠/g, 0).replace(/١/g, 1).replace(/٢/g, 2).replace(/٣/g, 3).replace(/٤/g, 4).replace(/٥/g, 5).replace(/٦/g, 6).replace(/٧/g, 7).replace(/٨/g, 8).replace(/٩/g, 9)

    return '' + n
  },
  toNumber (n) {
    if (!n) return n
    n = '' + n
    n = this.fromArabicNumber(n)
    n = n.replace(/\,/g, '')
    // .replace(/[^\d+.-]/g, "");

    return '' + n
  },
  toNumberFormat (num = null, c) {
    // console.log(num);

    if (!num) {
      // console.log(num)
      return num
    }

    const splitArray = this.toNumber(num).split('.')
    const rgx = /(\d+)(\d{3})/

    while (rgx.test(splitArray[0])) {
      splitArray[0] = splitArray[0].replace(rgx, '$1' + ',' + '$2')
    }
    // console.log(splitArray);
    let t = splitArray.join('.')
    if (c) t = `${t} ${c}`
    return t
  },
  hijriYear () {
    const l = moment().locale()
    moment.locale('en')
    const date = moment().format('iYYYY')
    moment.locale(l)
    return date
  },
  hijriMonth () {
    const l = moment().locale()
    moment.locale('en')
    const date = moment().format('iM')
    moment.locale(l)
    return date
  },
  hijriDay () {
    const l = moment().locale()
    moment.locale('en')
    const date = moment().format('iD')
    moment.locale(l)
    return date
  },
  todayDate () {
    const l = moment().locale()
    moment.locale('en')
    const date = moment().format('YYYY-MM-DD')
    moment.locale(l)
    return date
  },
  todayTime () {
    const l = moment().locale()
    moment.locale('en')
    const time = moment().format('HH:mm')
    moment.locale(l)
    return time
  }
}

export default { Tools }
