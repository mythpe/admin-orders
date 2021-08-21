

import { camelCase, isArray, isObject, startsWith, upperFirst } from 'lodash'

const
  messages = {},
  locales = [],
  requireModule = require.context('@locales', true, /\.js$/),
  toUpper = (string) => {
    if (isObject(string) || isArray(string)) {
      for (let s in string)
        string[s] = toUpper(string[s])
      return string
    } else {
      return upperFirst(string)
    }
  }

requireModule.keys().forEach(fileName => {
  if (fileName === './index.js') return

  const locale = fileName.replace(/(\.\/|\.js)/g, '').split('/')[0]
  !messages[locale] && (messages[locale] = {})

  let
    file = fileName.split('/').slice(-1)[0].replace(/(\.\/|\.js)/g, '').toLowerCase(),
    m = requireModule(fileName),
    l = m.default || m

  l = toUpper(l)

  if (file.toLowerCase() === locale.toLowerCase() || file.toLowerCase() === 'global') {
    messages[locale] = {
      ...messages[locale],
      ...l
    }
  } else {
    let key = camelCase(file)
    if (startsWith(file, '$'))
      key = `${file}`

    messages[locale][key] = l
  }
  locales.indexOf(locale) < 0 && locales.push(locale)
})
// console.log(messages, locales);

export default messages
export { messages, locales }
