

import { camelCase } from 'lodash'

const files = require.context('./requests', true, /\.js$/)
let methods = {}
for (const file of files.keys()) {
  let method = files(file)
  method = method.default || method

  let name = file.replace(/index.js/, '').replace(/^\.\//, '').replace(/\.\w+$/, '')

  name = camelCase(name)
  // console.log(method, name)

  methods[name] = method
}
// console.log(methods)

const Api = {
  methods
}
export default Api
