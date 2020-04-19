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

  sendLogout() {
    return this.call('post', '/api/logout')
  }

  getUser() {
    return this.call('get', '/api/get_user')
  }

  changeLang(langData) {
    return this.call('post', '/api/change_lang', langData)
  }

  editSetting(settingData) {
    return this.call('post', '/api/edit_setting', settingData)
  }

  uploadAvatar(avatarData) {
    return this.call('post', '/api/upload_avatar', avatarData, true)
  }

  getUserList() {
    return this.call('get', '/api/user')
  }

  addUser(userData) {
    return this.call('post', '/api/user', userData)
  }

  editUser(userData, id) {
    return this.call('put', '/api/user/' + id, userData)
  }

  editUserType(userData, id) {
    return this.call('patch', '/api/user/' + id, userData)
  }

  resetPassword(id) {
    return this.call('post', '/api/user/password_reset/' + id, {})
  }

  getGroupList() {
    return this.call('get', '/api/group')
  }

  addGroup(groupData) {
    return this.call('post', '/api/group', groupData)
  }

  editGroup(groupData, id) {
    return this.call('put', '/api/group/' + id, groupData)
  }

  editGroupMember(groupData, id) {
    return this.call('patch', '/api/group/' + id, groupData)
  }

  removeGroup(id) {
    return this.call('delete', '/api/group/' + id)
  }
}

export default Api