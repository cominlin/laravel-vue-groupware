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
            <v-text-field
                label="Username"
                name="username"
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
          </v-card-text>
        </v-card>

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
    border: 5px solid #ccc;
  }
</style>