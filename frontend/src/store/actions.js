import {
  GET_USER_LIST,
  GET_GROUP_LIST,
  GET_SCHEDULE_CATEGORY_LIST
} from './mutation-list'

export default {
  async getUserList({commit}) {
    return new Promise((resolve, reject) => {
      window.api.getUserList().then(res => {
        commit(GET_USER_LIST, res.data)
        resolve()
      }, error => {
        reject(error)
      })
    })
  },

  async getGroupList({commit}) {
    return new Promise((resolve, reject) => {
      window.api.getGroupList().then(res => {
        commit(GET_GROUP_LIST, res.data.groups)
        resolve()
      }, error => {
        reject(error)
      })
    })
  },

  async getScheduleCategoryList({commit}) {
    return new Promise((resolve, reject) => {
      window.api.getScheduleCategory().then(res => {
        commit(GET_SCHEDULE_CATEGORY_LIST, res.data.categories)
        resolve()
      }, error => {
        reject(error)
      })
    })
  },
}