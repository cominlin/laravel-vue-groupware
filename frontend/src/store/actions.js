import {
  GET_USER_LIST
} from './mutation-list'

export default {
  async getUserList({commit}) {
    return new Promise((resolve, reject) => {
      window.api.getUserList().then(res => {
        commit(GET_USER_LIST, res.data.user)
        resolve()
      }, error => {
        reject(error)
      })
    })
  }
}