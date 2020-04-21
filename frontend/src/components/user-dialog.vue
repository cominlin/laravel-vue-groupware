<template>
  <v-dialog :value="showUserDialog" @input="$emit('close')" max-width="600px">
    <v-card>
      <v-card-text>
        <v-btn text icon @click="$emit('close')" class="c-close"><v-icon>mdi-close</v-icon></v-btn>
        <div class="c-profile mb-3">
          <div :style="avatarStyle" />
          <div class="c-name">
            <div class="c-name__block">
              <p class="c-name__title">{{ selectedUser.name }}
                <span class="red--text" v-if="selectedUser.state === 1">【退職】</span>
              </p>
              <p class="c-name__text">{{ selectedUser.kana }}</p>
              <a class="c-link" :href="'mailto:' + selectedUser.email"><p class="c-name__text">{{ selectedUser.email }}</p></a>
            </div>
          </div>
        </div>
        <v-divider />
        <v-row>
          <v-col cols="4">
            <div class="body-2 font-weight-bold mt-3">組織</div>
          </v-col>
          <v-col cols="8">
            <div class="body-1 mt-3">
              <div v-for="(g, i) in selectedUser.groups" :key="'g' + i">{{ g.name }}</div>
            </div>
          </v-col>
          <v-col cols="4">
            <div class="body-2 font-weight-bold mt-3">タイムゾーン</div>
          </v-col>
          <v-col cols="8">
            <div class="body-1 mt-3">{{ selectedUser.timezone }}</div>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
  import { defaultLoginData } from '../static-data'
  export default {
    name: "user-dialog",
    props: {
      showUserDialog: Boolean,
      selectedUser: {
        type: Object,
        default: () => Object.assign({}, defaultLoginData)
      },
    },
    computed: {
      userAvatarDictionary() {
        return this.$store.getters.userAvatarDictionary
      },
    },
    data() {
      return {
        avatarStyle: {
          backgroundImage: null,
          width: '200px',
          height: '200px',
          backgroundSize: '100%',
          margin: 'auto',
          position: 'relative'
        },
      }
    },
    created() {
      this.avatarStyle.backgroundImage = 'url("' + this.userAvatarDictionary[this.selectedUser.id] + '")'
    },
    watch: {
      selectedUser(val) {
        this.avatarStyle.backgroundImage = 'url("' + this.userAvatarDictionary[val.id] + '")'
      }
    }
  }
</script>

<style scoped>

  .c-close {
    position: absolute !important;
    top: 5px;
    right: 5px;
  }

  .c-close:hover {
    transform: rotate(90deg);
    transition: all .1s;
  }

  .c-profile {
    width: 100%;
    display: flex;
  }

  .c-name {
    flex: 1;
    text-align: center;
    padding: 10px;
  }

  .c-name__block {
    display: inline-block;
    text-align: left;
  }

  .c-name__title {
    margin-top: 30px;
    font-size: 28px;
  }

  .c-name__text {
    font-size: 18px;
  }

  @media screen and (max-width: 621px) {
    .c-name__title {
      font-size: 24px;
    }

    .c-name__text {
      font-size: 14px;
    }
  }
</style>
