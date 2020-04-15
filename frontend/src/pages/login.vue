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
          cols="12"
          sm="8"
          md="4"
          class="c-form"
      >
        <h1>Groupware</h1>
        <v-text-field
            label="Login"
            name="login"
            prepend-icon="mdi-account"
            type="text"
        />

        <v-text-field
            id="password"
            label="Password"
            name="password"
            prepend-icon="mdi-lock"
            type="password"
        />
        <v-btn class="mt-3" color="primary">Login</v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
  export default {
    name: "login"
  }
</script>

<style scoped>
  .c-form {
    padding: 15px;
    /*background-color: rgba(255,255,255,.8);*/
    border: 3px solid #eee;
  }
</style>