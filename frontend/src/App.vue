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
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-view-dashboard</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Dashboard</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-settings</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Settings</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
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
      {{ user.name }}
    </v-app-bar>
    <v-content>
      <transition name="fade" mode="out-in">
        <router-view
            transition="fade-transition"
            :key="$route.fullPath"
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
          <v-list-tile
              :key="index"
              @click="changeLang(item.value)"
              v-for="(item, index) in languageArray"
          >
            <v-list-tile-title>{{ item.text }}</v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-menu>
    </v-footer>
  </v-app>
</template>

<script>
  import { defaultLoginData, languageOptions } from './static-data'
  import { mapState } from 'vuex'
  export default {
    name: 'App',
    components: {},
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
</style>