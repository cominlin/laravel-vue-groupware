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
  import { mapState } from 'vuex'
  import WaitingItem from './components/waiting-item'
  import LoadingItem from './components/loading-item'
  export default {
    name: 'App',
    components: {
      LoadingItem, WaitingItem
    },
    computed: {
      ...mapState([
        'isWaiting'
      ]),
      noNav() {
        return this.$route.name === 'login' || this.$route.name === '404'
      },
    },
    data: () => ({
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
      Event.$on('userLoggedIn', () => {
        this.authenticated = true
        this.user = window.auth.user
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
      changeLang(lang) {
        this.$i18n.locale = lang
        window.setValidateLocale(lang)
        window.api.changeLang({lang: lang}).then(() => {
          window.auth.getUser()
        })
      },
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