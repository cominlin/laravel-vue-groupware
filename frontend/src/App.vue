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
      <v-toolbar-title>Application</v-toolbar-title>
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
    </v-footer>
  </v-app>
</template>

<script>
  export default {
    name: 'App',
    components: {},
    computed: {
      noNav() {
        return this.$route.name === 'login' || this.$route.name === '404'
      },
    },
    data: () => ({
      drawer: true,
    }),
    created() {
      this.$vuetify.theme.dark = true
    },
  };
</script>

<style scoped>
  .c-fullHeight {
    height: 100%;
  }
</style>