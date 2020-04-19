<template>
  <v-container grid-list-md>
    <p class="headline c-pageTitle">ユーザー管理</p>
    <v-btn dark class="mb-2" @click="addUser" v-show="!showRetired" :loading="isWaiting">
      ユーザー新規作成
      <v-icon right>mdi-plus</v-icon>
    </v-btn>
    <v-btn dark class="mb-2 ml-2" @click="showRetiredList" :disabled="showForm" v-show="!showRetired" :loading="isWaiting">
      退職したユーザー
    </v-btn>
    <v-btn dark class="mb-2 ml-2" @click="backUserList" v-show="showRetired" :loading="isWaiting">
      ユーザーリストに戻る
    </v-btn>
    <user-dialog
        :show-user-dialog="showUserDialog"
        :selected-user="selectedUser"
        @close="closeDialog"
    />
    <v-dialog
        v-model="showForm"
        width="800"
        persistent
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
          <span class="body-2 red--text ml-2">＊は必須な項目です</span>
        </v-card-title>
        <v-card-text>
          <v-container grid-list-md>
            <v-row>
              <v-col cols="4">
                <v-text-field
                    name="name"
                    v-model="editedUser.user.name"
                    label="名前＊"
                    :counter="100"
                    data-vv-as="名前"
                    v-validate="'required|max:100'"
                    :error-messages="errors.collect('name')"
                />
              </v-col>
              <v-col cols="4">
                <v-text-field
                    name="kana"
                    v-model="editedUser.user.kana"
                    label="よみがな＊"
                    :counter="100"
                    data-vv-as="よみがな"
                    v-validate="'max:100'"
                    :error-messages="errors.collect('kana')"
                />
              </v-col>
              <v-col cols="4">
                <v-text-field
                    name="email"
                    v-model="editedUser.user.email"
                    label="Email＊"
                    :counter="50"
                    data-vv-as="Email"
                    v-validate="'required|email|max:100'"
                    :error-messages="errors.collect('email')"
                />
              </v-col>
              <v-col cols="12">
                <h3>組織</h3>
                <v-row>
                  <v-col cols="3" v-for="(g, i) in groupList" :key="'g' + i">
                    <v-checkbox
                        class="my-0"
                        v-model="editedUser.groups"
                        :label="g.name"
                        :value="g.id"
                    />
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="12" v-if="editedUser.id !== 1">
                <h3>権限</h3>
                <v-select
                  v-model="editedUser.user.type"
                  :items="authorityOptions"
                  :item-text="getTranslate"
                />
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn color="blue darken-1" text large @click.native="close" :loading="isWaiting">取消</v-btn>
          <v-btn class="mr-3" dark large @click.native="save" :loading="isWaiting">登録</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card class="mt-2" v-show="!showRetired">
      <v-card-title>
        <v-layout wrap>
          <v-select
              clearable
              v-model="selectedGroup"
              :items="groupList"
              item-text="name"
              item-value="id"
              label="組織"
          />
          <v-spacer />
          <v-text-field
              v-model="searchText"
              append-icon="mdi-magnify"
              label="Search by name, email"
              single-line
              hide-details
          />
        </v-layout>
      </v-card-title>
      <v-data-table
          :headers="headers"
          :items="filteredList"
          class="elevation-1"
          :footer-props="{ itemsPerPageOptions: [25, 50, 100, { text: 'All', value: -1 }] }"
      >
        <template v-slot:item.groups="{ item }">
          <span v-for="(g, i) in item.groups" class="c-groupText" :key="'g' + i">{{ g.name }}</span>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn icon class="mx-0" @click="editUser(item)" :loading="isWaiting">
            <v-icon color="teal">mdi-pencil</v-icon>
          </v-btn>
          <v-btn icon class="mx-0" @click="resetPassword(item)" :loading="isWaiting">
            <v-icon color="yellow darken-2">mdi-lock-reset</v-icon>
          </v-btn>
          <v-btn icon class="mx-0" @click="setResign(item, 0)" v-if="item.id !== 1" :loading="isWaiting">
            <v-icon color="pink" small>mdi-account-off</v-icon>
          </v-btn>
        </template>
        <template slot="no-data">
          <v-alert :value="true" color="grey" icon="mdi-alert">
            ユーザーがいません
          </v-alert>
        </template>
      </v-data-table>
    </v-card>
    <v-data-table
        v-show="showRetired"
        :headers="headersRetired"
        :items="retiredList"
        class="elevation-1"
        :footer-props="{ itemsPerPageOptions: [25, 50, 100, { text: 'All', value: -1 }] }"
    >
      <template v-slot:item.actions="{ item }">
        <v-btn icon class="mx-0" @click="setResign(item, 1)" :loading="isWaiting">
          <v-icon color="blue darken-1" small>mdi-undo-variant</v-icon>
        </v-btn>
      </template>
      <template slot="no-data">
        <v-alert :value="true" color="grey" icon="mdi-alert">
          退職したユーザーがいません
        </v-alert>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
  import { mapMutations, mapState } from 'vuex'
  import { defaultLoginData, defaultUserFormData, userAuthorities, imageBaseUrl } from '../../static-data'
  import defaultAvatar from '../../assets/user.jpg'
  import UserDialog from '../../components/user-dialog'

  export default {
    name: "user-page",
    components: {
      'user-dialog': UserDialog,
    },
    props: {
      user: Object,
      isWaiting: Boolean
    },
    computed: {
      ...mapState([
        'groupList', 'userList', 'retiredList'
      ]),
      formTitle() {
        return this.editType === 0 ? '新規作成' : '編集'
      },
      filteredList() {
        let s = this.searchText
        let keys = ['name', 'kana', 'email']
        let temp = []
        if (s !== '') {
          temp = this.userList.filter(item => keys.some(k => item[k].toUpperCase().includes(s.toUpperCase())))
        } else {
          temp = this.userList
        }
        if (this.selectedGroup !== undefined && this.selectedGroup !== null) {
          temp = temp.filter(item => item.groups.some(g => g.id === this.selectedGroup))
        }
        return temp
      }
    },
    data() {
      return {
        showUserDialog: false,
        authorityOptions: userAuthorities,
        selectedUser: Object.assign({}, defaultLoginData),
        showForm: false,
        showHired: false,
        showRetired: false,
        editType: 0,
        editedUser: JSON.parse(JSON.stringify(defaultUserFormData)),
        editingUserId: 0,
        resignMessage: [
          ['さんを退職に設定してもよろしいでしょうか？', 'さんを退職に設定しました。'],
          ['さんを復職に設定してもよろしいでしょうか', 'さんを復職に設定しました']
        ],
        headers: [
          {
            text: '名前',
            align: 'left',
            value: 'name'
          },
          {
            text: 'Email',
            align: 'left',
            value: 'email'
          },
          {
            text: '組織',
            align: 'left',
            value: 'groups',
            sortable: false
          },
          {
            text: '編集／PW／退職',
            align: 'center',
            value: 'actions',
            sortable: false
          }
        ],
        headersRetired: [
          {
            text: '名前',
            align: 'left',
            value: 'name',
            sortable: false
          },
          {
            text: 'Email',
            align: 'left',
            value: 'email',
            sortable: false
          },
          {
            text: '回復',
            align: 'center',
            value: 'actions',
            sortable: false
          }
        ],
        searchText: '',
        selectedGroup: null,
      }
    },
    created() {
      if (this.user.type !== 3) {
        this.$router.push('/home')
      }
    },
    methods: {
      ...mapMutations([
        'GET_GROUP_LIST', 'GET_USER_LIST', 'GET_RETIRED_LIST'
      ]),
      showUserAvatar(u) {
        return u.profile.avatar === null ?
            defaultAvatar : imageBaseUrl + u.profile.avatar
      },
      getTranslate(item) {
        return this.$t(item.text)
      },
      setFormData(u) {
        let temp = JSON.parse(JSON.stringify(defaultUserFormData))
        for(let key in temp.user) {
          temp.user[key] = u[key]
        }
        temp.groups = u.groups.map(item => item.id)
        return temp
      },
      showDetail(u) {
        this.selectedUser = Object.assign({}, u)
        this.showUserDialog = true
      },
      addUser() {
        this.showForm = true
      },
      editUser(u) {
        this.editingUserId = u.id
        this.editedUser = this.setFormData(JSON.parse(JSON.stringify(u)))
        this.editType = 1
        this.showForm = true
        window.scrollTo(0, 0)
      },
      setResign(u, type) {
        let vm = this
        if (confirm(u.name + vm.resignMessage[type][0])) {
          window.setWaiting(true)
          window.api.editUserType({type: type }, u.id).then(res => {
            vm.GET_GROUP_LIST(res.data.groups)
            vm.GET_USER_LIST(res.data)
            Event.$emit('showAlert', u.name + vm.resignMessage[type][1])
            window.setWaiting(false)
          }, error => {
            Event.$emit('showAlert', error.data.message)
            window.setWaiting(false)
          })
        }
      },
      resetPassword(u) {
        if (confirm(u.name + 'さんのパスワードを再設定してもよろしいでしょうか？')) {
          window.setWaiting(true)
          window.api.resetPassword(u.id).then(() => {
            Event.$emit('showAlert', u.name + 'さんのパスワードを再設定して、メールを送りました。')
            window.setWaiting(false)
          }, error => {
            Event.$emit('showAlert', error.data.message)
            window.setWaiting(false)
          })
        }
      },
      save() {
        let vm = this
        vm.$validator.validateAll().then(result => {
          if (result) {
            window.setWaiting(true)
            if (vm.editType === 0) {
              window.api.addUser(vm.editedUser).then(res => {
                vm.GET_GROUP_LIST(res.data.groups)
                vm.GET_USER_LIST(res.data)
                vm.close()
                Event.$emit('showAlert', 'ユーザー作成しました。')
                window.setWaiting(false)
              }, error => {
                Event.$emit('showAlert', error.data.message)
                window.setWaiting(false)
              })
            } else {
              window.api.editUser(vm.editedUser, vm.editingUserId).then(res => {
                window.console.log(res)
                vm.GET_GROUP_LIST(res.data.groups)
                vm.GET_USER_LIST(res.data.users)
                vm.close()
                Event.$emit('showAlert', '編集しました。')
                window.setWaiting(false)
              }, error => {
                window.console.log(error)
                Event.$emit('showAlert', error.data.message)
                window.setWaiting(false)
              })
            }
          }
        })
      },
      closeDialog() {
        this.showUserDialog = false
        this.selectedUser = Object.assign({}, defaultLoginData)
      },
      close() {
        this.showForm = false
        setTimeout(() => {
          this.editedUser = JSON.parse(JSON.stringify(defaultUserFormData))
          this.editType = 0
        }, 300)
      },
      showRetiredList() {
        this.showRetired = true
      },
      backUserList() {
        this.showRetired = false
      }
    },
    watch: {
      showForm(val) {
        val || this.close()
      }
    },
  }
</script>

<style scoped>
  .c-groupText:not(:first-child):before {
    content: ", ";
  }

  .c-avatar {
    cursor: pointer;
  }
</style>
