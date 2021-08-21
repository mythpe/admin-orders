

import routeAllies from '@routes/config'

let routes = []
const requireModule = require.context('@routes', true, /\.js$/)
// const requireModule = require.context("@routes", true, /index.js$/);
requireModule.keys().forEach(fileName => {
  // console.log(fileName)
  if (fileName.split('/').pop() !== 'index.js') return
  const m = requireModule(fileName)
  const l = m.default || m
  // console.log(l)
  routes = [...routes, ...l]
})
// console.log(routes)
/**
 * Not Found route
 */
routes.push({
  path: '*',
  name: 'NotFound',
  component: () => import(/* webpackChunkName: "FourOuFour" */ '@views/NotFound')
})

routes.push({
  path: routeAllies.auth.logout.path,
  name: routeAllies.auth.logout.name,
  component: () => import(/* webpackChunkName: "Logout" */ '@views/Logout')
})
export default routes
