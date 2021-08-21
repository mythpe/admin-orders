

import camelCase from 'lodash/camelCase'

const modules = {},
  requireModule = require.context('./modules', false, /\.js$/)

requireModule.keys().forEach(fileName => {
  if (fileName === './index.js') return

  const moduleName = camelCase(fileName.replace(/(\.\/|\.js)/g, ''))
  const m = requireModule(fileName)
  modules[moduleName] = m.default || m
})

export default modules
