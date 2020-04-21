<template>
  <v-container grid-list-md>
    <p class="headline c-pageTitle">{{ $t('title.user') }}</p>
    <v-btn dark class="mb-2" @click="addUser" v-show="!showResigned" :loading="isWaiting">
      {{ $t('user.add') }}
      <v-icon right>mdi-plus</v-icon>
    </v-btn>
    <v-btn dark class="mb-2 ml-2" @click="showResignedList" :disabled="showForm" v-show="!showResigned" :loading="isWaiting">
      {{ $t('user.resigned') }}
    </v-btn>
    <v-btn dark class="mb-2 ml-2" @click="backUserList" v-show="showResigned" :loading="isWaiting">
      {{ $t('user.back_user_list') }}
    </v-btn>
    <v-card class="mt-2" v-show="!showResigned">
      <v-card-title>
        <v-layout wrap>
          <v-select
              clearable
              v-model="selectedGroup"
              :items="groupList"
              item-text="name"
              item-value="id"
              :label="$t('object.group')"
          />
          <v-spacer />
          <v-text-field
              v-model="searchText"
              append-icon="mdi-magnify"
              :label="$t('user.search_user')"
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
        <template v-slot:item.name="{ item }">
          <v-btn text color="blue" @click="showDetail(item)">{{ item.name }}</v-btn>
        </template>
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
            {{ $t('user.no_user_data') }}
          </v-alert>
        </template>
      </v-data-table>
    </v-card>
    <v-data-table
        v-show="showResigned"
        :headers="headersResigned"
        :items="resignedList"
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
          {{ $t('user.no_resigned_data') }}
        </v-alert>
      </template>
    </v-data-table>
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
          <span class="headline">{{ $t(`form_title.${this.editType}`) }}</span>
          <span class="body-2 red--text ml-2">{{ $t('message.required') }}</span>
        </v-card-title>
        <v-card-text>
          <v-container grid-list-md>
            <v-row>
              <v-col cols="4">
                <v-text-field
                    name="name"
                    v-model="editedUser.user.name"
                    :label="$t('object.name') + '＊'"
                    :counter="100"
                    :data-vv-as="$t('object.name')"
                    v-validate="'required|max:100'"
                    :error-messages="errors.collect('name')"
                />
              </v-col>
              <v-col cols="4">
                <v-text-field
                    name="kana"
                    v-model="editedUser.user.kana"
                    :label="$t('object.kana') + '＊'"
                    :counter="100"
                    :data-vv-as="$t('object.kana')"
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
              <v-col cols="6">
                <v-select
                    label="タイムゾーン"
                    :menu-props="{maxHeight:'500'}"
                    v-model="editedUser.user.timezone"
                    :items="timeOptions"
                />
              </v-col>
              <v-col cols="6" v-if="editedUser.id !== 1">
                <h3>{{ $t('object.auth') }}</h3>
                <v-select
                    v-model="editedUser.user.type"
                    :items="authorityOptions"
                    :item-text="getTranslate"
                />
              </v-col>
              <v-col cols="12">
                <h3>{{ $t('object.group') }}</h3>
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
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn color="blue darken-1" text large @click.native="close" :loading="isWaiting">{{ $t('button.cancel') }}</v-btn>
          <v-btn class="mr-3" dark large @click.native="save" :loading="isWaiting">{{ $t('button.save') }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
  import { mapMutations, mapState } from 'vuex'
  import { defaultLoginData, defaultUserFormData, userAuthorities, timeZoneOptions } from '../../static-data'
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
        'groupList', 'userList', 'resignedList'
      ]),
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
      },
      headers() {
        return [
          {
            text: this.$t('object.name'),
            align: 'left',
            value: 'name'
          },
          {
            text: 'Email',
            align: 'left',
            value: 'email'
          },
          {
            text: this.$t('object.group'),
            align: 'left',
            value: 'groups',
            sortable: false
          },
          {
            text: this.$t('user.table_actions'),
            align: 'center',
            value: 'actions',
            sortable: false
          }
        ]
      },
      headersResigned() {
        return [
          {
            text: this.$t('object.name'),
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
            text: this.$t('user.table_actions2'),
            align: 'center',
            value: 'actions',
            sortable: false
          }
        ]
      }
    },
    data() {
      return {
        showUserDialog: false,
        timeOptions: timeZoneOptions,
        authorityOptions: userAuthorities,
        selectedUser: Object.assign({}, defaultLoginData),
        showForm: false,
        showHired: false,
        showResigned: false,
        editType: 'new',
        editedUser: JSON.parse(JSON.stringify(defaultUserFormData)),
        editingUserId: 0,
        resignMessage: [
          ['user.resign_ask', 'user.resign_finish'],
          ['user.recover_ask', 'user.recover_finish']
        ],
        searchText: '',
        selectedGroup: null,
      }
    },
    created() {
      if (this.user.type !== 3) {
        this.$router.push('/')
      }
    },
    methods: {
      ...mapMutations([
        'GET_GROUP_LIST', 'GET_USER_LIST'
      ]),
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
        this.editType = 'edit'
        this.showForm = true
        window.scrollTo(0, 0)
      },
      setResign(u, type) {
        let vm = this
        if (confirm(vm.$t(vm.resignMessage[type][0], { item: u.name }))) {
          window.setWaiting(true)
          window.api.editUserType({type: type }, u.id).then(res => {
            vm.GET_GROUP_LIST(res.data.groups)
            vm.GET_USER_LIST(res.data)
            Event.$emit('showAlert', vm.$t(vm.resignMessage[type][1], { item: u.name }))
            window.setWaiting(false)
          }, error => {
            Event.$emit('showAlert', error.data.message)
            window.setWaiting(false)
          })
        }
      },
      resetPassword(u) {
        if (confirm(this.$t('user.reset_password_ask', { item: u.name }))) {
          window.setWaiting(true)
          window.api.resetPassword(u.id).then(() => {
            Event.$emit('showAlert', this.$t('user.reset_password_finish', { item: u.name }))
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
            if (vm.editType === 'new') {
              window.api.addUser(vm.editedUser).then(res => {
                vm.GET_GROUP_LIST(res.data.groups)
                vm.GET_USER_LIST(res.data)
                vm.close()
                Event.$emit('showAlert', vm.$t('message.added', { item: vm.$t('object.user') }))
                window.setWaiting(false)
              }, error => {
                Event.$emit('showAlert', error.data.message)
                window.setWaiting(false)
              })
            } else {
              window.api.editUser(vm.editedUser, vm.editingUserId).then(res => {
                vm.GET_GROUP_LIST(res.data.groups)
                vm.GET_USER_LIST(res.data)
                vm.close()
                Event.$emit('showAlert', vm.$t('message.edited'))
                window.setWaiting(false)
              }, error => {
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
          this.editType = 'new'
        }, 300)
      },
      showResignedList() {
        this.showResigned = true
      },
      backUserList() {
        this.showResigned = false
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
