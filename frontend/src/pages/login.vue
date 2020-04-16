<template>
  <v-container
      class="fill-height"
      fluid
  >
    <v-row
        align="center"
        justify="center"
    >
      <v-col
          cols="6"
          sm="4"
      >
        <v-card light class="c-form">
          <v-card-text>
            <h1>Groupware</h1>
            <v-alert v-model="alert.show" :type="alert.type" dismissible>{{ alert.text }}</v-alert>
            <v-text-field
                label="Username"
                name="username"
                prepend-icon="mdi-account"
                type="text"
                v-model="loginData.username"
            />

            <v-text-field
                id="password"
                label="Password"
                name="password"
                prepend-icon="mdi-lock"
                type="password"
                v-model="loginData.password"
            />
            <v-btn class="mt-3" color="primary" @click="login" :loading="loading">Login</v-btn>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
  export default {
    name: "login",
    data() {
      return {
        loading: false,
        loginData: {
          username: '',
          password: '',
        },
        alert: {
          show: false,
          type: 'error',
          text: ''
        }
      }
    },
    mounted() {
      window.setLoading(false)
    },
    methods: {
      login() {
        let vm = this
        vm.$validator.validateAll().then(result => {
          if (result) {
            vm.loading = true
            window.api.sendLogin(vm.loginData).then(({ data }) => {
              window.auth.login(data.token, data.user, data.notifications)
              vm.$router.push('/')
              vm.loading = false
            }, error => {
              vm.showAlert(error.data.message)
              vm.loading = false
            })
          }
        })
      },
      showAlert (text, type = 'error') {
        this.alert = {
          show: true,
          text: text,
          type: type
        }
      }
    }
  }
</script>

<style scoped>
  .c-form {
    border: 5px solid #ccc;
  }
</style>