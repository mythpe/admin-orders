

export const LOGIN_ROUTE_NAME = 'login'
export const LOGOUT_ROUTE_NAME = 'logout'
export const HOME_ROUTE_NAME = 'home'
export const USER_HOME_ROUTE_NAME = 'dashboard'

const routeAllies = {
  landing: {
    home: HOME_ROUTE_NAME
  },
  auth: {
    login: LOGIN_ROUTE_NAME,
    logout: {
      path: `/${LOGOUT_ROUTE_NAME}`,
      name: LOGOUT_ROUTE_NAME
    }
  },
  user: {
    home: USER_HOME_ROUTE_NAME,
    profile: 'profile',
    index: 'user-index'
  },
  setting: 'setting'
}
// console.log(routeAllies)
export default routeAllies
