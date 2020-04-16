import { defaultLoginData } from './static-data'

class Auth {
  constructor() {
    this.token = window.localStorage['gw_token'] ? window.localStorage['gw_token'] : null
    this.user = window.localStorage['gw_user'] ? JSON.parse(window.localStorage['gw_user']) : Object.assign({}, defaultLoginData)
    if (this.token) {
      window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token
      this.getUser()
    }
  }

  getUser() {
    return new Promise((resolve, reject) => {
      window.api.getUser().then(res => {
        this.user = res.data.user
        window.localStorage.setItem('gw_user', JSON.stringify(res.data.user))
        Event.$emit('userLoggedIn', res.data.notifications)
        window.getVuexData()
        resolve(res)
      }, error => {
        this.logout()
        reject(error)
      })
    })
  }

  login(token, user, notifications) {
    window.localStorage.setItem('gw_token', token)
    window.localStorage.setItem('gw_user', JSON.stringify(user))

    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

    this.token = token
    this.user = user

    Event.$emit('userLoggedIn', notifications)
    window.getVuexData()
  }

  logout() {
    window.localStorage.removeItem('gw_token')
    window.localStorage.removeItem('gw_user')

    this.token = null
    this.user = null
    window.axios.defaults.headers.common['Authorization'] = ''
    Event.$emit('userLoggedOut')
  }
  check() {
    return !!this.token && this.user.id !== undefined && this.user.id !== 0 && this.user.id !== null
  }
}

export default Auth