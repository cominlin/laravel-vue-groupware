<template>
  <v-app id="inspire">
    <v-content class="c-fullHeight" v-if="noNav">
      <router-view/>
    </v-content>
    <v-navigation-drawer
        v-model="drawer"
        app
        clipped
        fixed
        v-if="!noNav"
    >
      <v-list>
        <template v-for="(i, index) in listItems">
          <v-list-item
              exact
              ripple
              :to="{ name: i.routeName }"
              :key="'list' + index">
            <v-list-item-action>
              <v-icon>{{ i.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ $t(`title.${i.routeName}`) }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
        app
        clipped-left
        v-if="!noNav"
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"/>
      <v-toolbar-title>Groupware</v-toolbar-title>
      <v-spacer />
      <v-badge overlap color="red" class="mr-2" v-model="showBadge">
        <template v-slot:badge>
          <span class="c-notificationBadge" @click="showNotification">{{ unreadNumber }}</span>
        </template>
        <v-icon @click="showNotification">fa-bell</v-icon>
        <notification-list
            :user="user"
            v-show="showNotificationList"
            @close="closeNotification"
        />
      </v-badge>
      <v-menu offset-y class="hidden-xs-only">
        <template v-slot:activator="{ on }">
          <v-btn text v-on="on">
            <!--<v-avatar size="36">-->
              <!--<img :src="userAvatarDictionary[user.id]">-->
            <!--</v-avatar>-->
            {{ user.name }}
            <v-icon>mdi-menu-down</v-icon>
          </v-btn>
        </template>
        <v-list>
          <v-list-item :to="{ name: 'setting' }">
            <v-list-item-title>設定</v-list-item-title>
          </v-list-item>
          <v-list-item @click.prevent="logout">
            <v-list-item-title>ログアウト</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>
    <v-content>
      <transition name="fade" mode="out-in">
        <router-view
            transition="fade-transition"
            :key="$route.fullPath"
            :user="user"
            :is-waiting="isWaiting"
            v-if="!noNav"
        />
      </transition>
    </v-content>

    <v-footer app v-if="!noNav">
      <v-spacer/>
      <span>&copy; 2020 Cominlin</span>
      <v-spacer/>
      <v-menu offset-y>
        <template v-slot:activator="{ on }">
          <v-btn
              right
              small
              v-on="on"
          >
            {{ languageArray[$i18n.locale].text }}
          </v-btn>
        </template>
        <v-list>
          <v-list-item
              :key="index"
              @click="changeLang(item.value)"
              v-for="(item, index) in languageArray"
          >
            <v-list-item-title>{{ item.text }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-footer>
    <v-snackbar
        v-model="alert.show"
        left
    >
      {{ alert.text }}
      <v-btn color="pink" text @click="alert.show = false">Close</v-btn>
    </v-snackbar>
    <waiting-item/>
    <loading-item/>
  </v-app>
</template>

<script>
  import { defaultLoginData, languageOptions } from './static-data'
  import { mapState, mapMutations } from 'vuex'
  import WaitingItem from './components/waiting-item'
  import LoadingItem from './components/loading-item'
  import NotificationList from './components/notification-list'
  import { isEmpty } from './common'
  export default {
    name: 'App',
    components: {
      LoadingItem, WaitingItem, NotificationList
    },
    computed: {
      ...mapState([
        'isWaiting', 'notificationList'
      ]),
      noNav() {
        return this.$route.name === 'login' || this.$route.name === '404'
      },
      unreadNumber() {
        let num = this.notificationList.filter(item => isEmpty(item.read_at)).length
        return num > 99 ? '99+' : num
      },
      showBadge() {
        return this.unreadNumber !== 0
      }
    },
    data: () => ({
      showNotificationList: false,
      authenticated: window.auth.check(),
      user: window.auth.user,
      drawer: null,
      alert: {
        show: false,
        text: ''
      },
      languageArray: languageOptions,
      listItems: [
        {
          routeName: 'home',
          icon: 'mdi-home'
        },
        {
          routeName: 'user',
          icon: 'mdi-account',
          authority: 'user_manage'
        },
        {
          routeName: 'group',
          icon: 'mdi-account-group',
          authority: 'group_manage'
        },
      ],
    }),
    created() {
      this.$vuetify.theme.dark = true
    },
    mounted() {
      this.$i18n.locale = window.auth.user.language
      window.setValidateLocale(window.auth.user.language)
      Event.$on('userLoggedIn', notifications => {
        this.GET_NOTIFICATION_LIST(notifications)
        this.authenticated = true
        this.user = window.auth.user
        this.joinChannel()
      })
      Event.$on('userLoggedOut', () => {
        this.authenticated = false
        this.user = Object.assign({}, defaultLoginData)
      })
      Event.$on('updateUser', () => {
        this.user = window.auth.user
      })
      Event.$on('showAlert', text => {
        this.alert = {
          show: true,
          text: text
        }
      })
    },
    methods: {
      ...mapMutations([
        'GET_NOTIFICATION_LIST', 'ADD_NEW_NOTIFICATION'
      ]),
      logout() {
        const vm = this
        window.api.sendLogout().then(() => {
          window.auth.logout()
          vm.$router.push('/login')
        }, () => {
          window.auth.logout()
          vm.$router.push('/login')
        })
      },
      showAlert(text) {
        this.alert = {
          show: true,
          text: text
        }
      },
      joinChannel() {
        let vm = this
        window.Echo.private('App.User.' + this.user.id)
            .notification(n => {
              vm.ADD_NEW_NOTIFICATION(Object.assign({ created_at: vm.$moment().format('YYYY-MM-DD HH:mm:ss'), read_at: null }, n))
            })
      },
      changeLang(lang) {
        this.$i18n.locale = lang
        window.setValidateLocale(lang)
        window.api.changeLang({lang: lang}).then(() => {
          window.auth.getUser()
        })
      },
      showNotification() {
        this.showNotificationList = !this.showNotificationList
      },
      closeNotification() {
        this.showNotificationList = false
      }
    },
    watch: {
      $route() {
        this.showNotificationList = false
      }
    }
  };
</script>

<style scoped>
  .c-fullHeight {
    height: 100%;
  }

  .fade-enter-active, .fade-leave-active {
    transition: opacity .3s;
  }

  .fade-enter, .fade-leave-to {
    opacity: 0;
  }
</style>
<style>
  .c-pageTitle {
    font-weight: bold !important;
  }
</style>