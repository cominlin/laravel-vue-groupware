import {
  SET_LOADING,
  SET_WAITING,
  GET_USER_LIST,
  GET_GROUP_LIST,
} from './mutation-list'

export default {
  [SET_LOADING] (state, data) {
    state.isLoading = data
  },

  [SET_WAITING] (state, data) {
    state.isWaiting = data
  },

  [GET_USER_LIST] (state, data) {
    state.userList = data.users
    state.resignedList = data.resigned_users
  },

  [GET_GROUP_LIST] (state, data) {
    state.groupList = data
  },
}