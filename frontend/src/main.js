import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import VueRouter from 'vue-router'
import VeeValidate from 'vee-validate'
import VeeValidateMessagesEN from 'vee-validate/dist/locale/en'
import VeeValidateMessagesJA from 'vee-validate/dist/locale/ja'
import VeeValidateMessagesTW from 'vee-validate/dist/locale/zh_TW'
import i18n from './i18n'
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

window.axios = require('axios')
window.Event = new Vue

new Vue({
  store,
  i18n,
  router: router,
  vuetify,
  render: h => h(App)
}).$mount('#app')