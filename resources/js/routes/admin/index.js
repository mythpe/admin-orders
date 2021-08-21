

import routes from '@routes/config'

export default [
  {
    path: '',
    component: () => import( /* webpackChunkName: "App" */ '@views/App'),
    meta: {
      auth: true
    },
    children: [
      {
        path: routes.user.home,
        name: routes.user.home,
        alias: '/',
        component: () => import(/* webpackChunkName: "Home" */ '@components/Dashboard')
      },
      {
        path: 'profile',
        name: routes.user.profile,
        component: () => import(/* webpackChunkName: "Profile" */ '@components/user/Profile')
      },
      {
        path: routes.user.index,
        name: routes.user.index,
        component: () => import(/* webpackChunkName: "users" */ '@components/user/Index')
      },
      {
        path: routes.setting,
        name: routes.setting,
        component: () => import(/* webpackChunkName: "setting" */ '@components/Setting')
      },
    ]
  }
]
