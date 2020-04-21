import { imageBaseUrl } from '../static-data'
import defaultAvatar from '../assets/user.jpg'

export default {
  userNameDictionary: state => {
    let users = {}
    state.userList.forEach(item => {
      users[item.id] = item.name
    })
    return users
  },

  userAvatarDictionary: state => {
    let users = {}
    state.userList.forEach(item => {
      users[item.id] = item.avatar === null ?
          defaultAvatar : imageBaseUrl + item.profile.avatar
    })
    return users
  },

  groupDictionary: state => {
    let group = {}
    state.groupList.forEach(item => {
      group[item.id] = item.name
    })
    return group
  },

  scheduleCategoryDictionary: state => {
    let forms = {}
    state.scheduleCategoryList.forEach(item => {
      forms[item.id] = { color: item.color, name: item.name }
    })
    return forms
  }
}