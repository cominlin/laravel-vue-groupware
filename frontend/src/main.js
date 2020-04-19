import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import VueRouter from 'vue-router'
import VeeValidate from 'vee-validate'
import VeeValidateMessagesEN from 'vee-validate/dist/locale/en'
import VeeValidateMessagesJA from 'vee-validate/dist/locale/ja'
import VeeValidateMessagesTW from 'vee-validate/dist/locale/zh_TW'
import i18n from './i18n'
import Auth from './auth.js'
import Api from './api.js'
import router from './router'
import store from './store'

Vue.config.productionTip = false
Vue.use(VeeValidate)
Vue.use(VueRouter)

window.setValidateLocale = lang => {
  let validateLang = {
    en: VeeValidateMessagesEN,
    ja: VeeValidateMessagesJA,
    tw: VeeValidateMessagesTW
  }
  VeeValidate.Validator.localize(lang, validateLang[lang])
}

window.setLoading = loading => {
  store.commit('SET_LOADING', loading)
}
window.setWaiting = waiting => {
  store.commit('SET_WAITING', waiting)
}

window.setWaiting(false)
window.setLoading(true)
window.getVuexData = () => {
  store.dispatch('getGroupList')
  store.dispatch('getUserList').then(() => {
    window.setLoading(false)
  })
}

window.axios = require('axios')
window.Event = new Vue
window.api = new Api()
window.auth = new Auth()

new Vue({
  store,
  i18n,
  router: router,
  vuetify,
  render: h => h(App)
}).$mount('#app')