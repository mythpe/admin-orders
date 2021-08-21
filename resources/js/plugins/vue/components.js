
import camelCase from 'lodash/upperFirst'
import upperFirst from 'lodash/upperFirst'
import Vue from 'vue'

const requireComponent = require.context('@components/base', true, /\.vue$/)
for (let file of requireComponent.keys()) {

  file = file.replace('./', '')

  const name = file.split('/').pop().replace(/\.\w+$/, '').replace(/\-+/, '')

  const componentName = `App${upperFirst(camelCase(name))}`
  // console.log(componentName)
  // console.log(file)
  Vue.component(componentName, () => import( `@components/base/${file}`))
}
