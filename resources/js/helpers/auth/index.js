

const Auth = {

  /**
   * default localstorage data
   */
  default: {
    token: null,
    user: null
  },

  /**
   * YOU MUST SET THIS VALUE AS User::COLUMN_ROLE_NAME
   * user role Key
   */
  role_key: 'role_code',

  /**
   * localstorage Key
   */
  localstorageKey: 'authentication_key',

  /**
   * get localstorage data
   *
   * @returns {any|{token: null}}
   */
  data (key = null) {
    const k = this.default
    let o = JSON.parse(localStorage.getItem(this.localstorageKey) || JSON.stringify(k)) || k
    return key === null || key === undefined || key === 'undefined' || !key ? o : o[key]
  },
  /**
   * save localstorage data
   * @param options
   */
  save (options) {
    const data = this.data()
    let save = { ...options }
    Object.keys(this.default).forEach(k => save[k] = _.has(options, k) ? options[k] : data[k])
    localStorage.setItem(this.localstorageKey, JSON.stringify(save))
  },

  /**
   * get access token from localstorage
   * @returns {string}
   */
  getAccessToken () {
    const { token } = this.data()
    return token || this.default.token
  },

  /**
   * get user data from localstorage
   * @returns {Object}
   */
  getUserData () {
    const { user } = this.data()
    return user || this.default.user
  },

  /**
   * is login from localstorage
   * @returns {boolean}
   */
  isLogin () {
    const { token } = this.data() || ''
    return token ? token.length > 0 : false
  },

  /**
   * Remove localstorage
   */
  logout () {
    localStorage.removeItem(this.localstorageKey)
  },

  /**
   * check user role
   * @returns {boolean}
   */
  is () {
    const { user } = this.data() || this.default
    if (!user) return false
    let t = user[this.role_key] || '', role = arguments.length === 1 ? arguments[0] : [...arguments]
    role = typeof role !== 'object' ? role.split(',') : Object.values(role)
    t = t.toLocaleLowerCase().trim()
    role.map((i, item) => item.toString().toLocaleLowerCase().trim())
    return role.indexOf(t) > -1
  },

  /**
   * Helpers
   * @returns {boolean}
   */
  isAdmin () {
    return this.is('admin')
  },

  set (key, value) {
    let data = this.data()
    data[key] = value
    this.save(data)
    return this
  },

  get (key) {
    return this.data(key)
  }
}

export default Auth
