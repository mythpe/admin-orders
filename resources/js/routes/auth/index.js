

import routes from '@routes/config'

export default [
  {
    path: '/',
    component: () => import(/* webpackChunkName: "App" */'@views/App'),
    meta: {
      // guest: true
    },
    children: [
      {
        path: routes.auth.login,
        name: routes.auth.login,
        meta: {
          guest: true
        },
        component: () => import(/* webpackChunkName: "Login" */ '@components/auth/Login')
      }
    ]
  }
]
