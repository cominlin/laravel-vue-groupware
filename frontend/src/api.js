class Api {
  constructor() {
  }

  call(requestType, url, data = null, file = false) {
    return new Promise((resolve, reject) => {
      window.axios({
        method: requestType,
        url: url,
        headers: { 'content-type': (file ? 'multipart/form-data' : 'application/json') },
        data: data
      }).then(response => {
        resolve(response)
      })
          .catch(({ response }) => {
            if (response.status === 401) {
              window.auth.logout()
              window.setWaiting(false)
              window.Vue.$router.push('/login')
            }
            reject(response)
          })
    })
  }

  sendLogin(loginData) {
    return this.call('post', '/api/login', loginData)
  }

  getUser() {
    return this.call('get', '/api/get_user')
  }

  changeLang(langData) {
    return this.call('post', '/api/change_lang', langData)
  }

  getUserList() {
    return this.call('get', '/api/user')
  }
}

export default Api