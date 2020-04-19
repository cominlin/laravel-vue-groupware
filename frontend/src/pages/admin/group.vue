<template>
  <v-container grid-list-md>
    <p class="headline c-pageTitle">組織管理</p>
    <v-btn dark class="mb-2" :loading="isWaiting" @click="showDialog = true">
      組織追加
      <v-icon right>mdi-plus</v-icon>
    </v-btn>
    <v-data-table
        :headers="headers"
        :items="groupList"
        hide-default-footer
        class="elevation-1"
    >
      <template v-slot:item.created_at="{ item }">
        {{ item.created_at | moment('YYYY/MM/DD (dd) HH:mm') }}
      </template>
      <template v-slot:item.actions="{ item }">
        <v-btn icon class="mx-0" @click="manageMember(item)">
          <v-icon color="blue" :loading="isWaiting">mdi-account-group</v-icon>
        </v-btn>
        <v-btn icon class="mx-0" @click="editGroup(item)">
          <v-icon color="teal" :loading="isWaiting">mdi-pencil</v-icon>
        </v-btn>
        <v-btn icon class="mx-0" @click="deleteGroup(item.id)">
          <v-icon color="pink" :loading="isWaiting">mdi-delete</v-icon>
        </v-btn>
      </template>
      <template slot="no-data">
        <v-alert :value="true" color="grey" icon="warning">
          組織がありません
        </v-alert>
      </template>
    </v-data-table>
    <v-dialog v-model="showDialog" max-width="500px" persistent>
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>
        <v-card-text>
          <v-container grid-list-md>
            <v-row>
              <v-col cols="12">
                <v-text-field
                    name="name"
                    v-model="editedItem.name"
                    label="組織名"
                    :counter="20"
                    data-vv-as="組織名"
                    v-validate="'required|max:20'"
                    :error-messages="errors.collect('name')"
                />
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn color="blue darken-1" text @click.native="close" :loading="isWaiting">取消</v-btn>
          <v-btn color="blue darken-1" text @click.native="save" :loading="isWaiting">登録</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog fullscreen hide-overlay persistent transition="dialog-bottom-transition" v-model="showMemberDialog">
      <v-card >
        <v-btn text icon @click="closeMemberDialog" class="c-close"><v-icon>mdi-close</v-icon></v-btn>
        <v-card-title>{{ showMemberItem.name }} - メンバー管理</v-card-title>
        <v-card-text>
          <v-row>
            <v-col cols="6">
              <v-card color="grey darken-3">
                <v-card-text>
                  <v-row>
                    <v-col cols="9">
                      <v-text-field
                          append-icon="mdi-magnify"
                          hide-details
                          label="Search by name, email"
                          single-line
                          v-model="searchUserText"
                      />
                    </v-col>
                    <v-col cols="3">
                      <v-btn @click="selectAllUser" dark>全部移動する</v-btn>
                    </v-col>
                  </v-row>
                  <div class="c-selectList">
                    <v-list color="grey darken-3" dense>
                      <v-list-item :disabled="members.indexOf(u) >= 0" :key="'f' + i"
                                   v-for="(u, i) in filteredUserIdList">
                        <v-list-item-avatar size="35">
                          <img :alt="userNameDictionary[u]" :src="userAvatarDictionary[u]"/>
                        </v-list-item-avatar>
                        <v-list-item-content>
                          <v-list-item-title v-html="userNameDictionary[u]"/>
                        </v-list-item-content>
                        <v-list-item-action>
                          <v-btn @click="addUser(u)" color="teal" text icon v-if="members.indexOf(u) < 0">
                            <v-icon>mdi-plus</v-icon>
                          </v-btn>
                        </v-list-item-action>
                      </v-list-item>
                    </v-list>
                  </div>
                </v-card-text>
              </v-card>
            </v-col>
            <v-col cols="6">
              <v-card color="blue-grey darken-3">
                <v-card-text>
                  <h4 class="c-memberTitle">メンバー
                    <v-btn @click="members = []" dark>全部削除する</v-btn>
                  </h4>
                  <div class="c-selectList">
                    <v-list color="blue-grey darken-3" dense>
                      <v-list-item :key="'user' + i" v-for="(u, i) in members">
                        <v-list-item-avatar size="35">
                          <img :alt="userNameDictionary[u]" :src="userAvatarDictionary[u]"/>
                        </v-list-item-avatar>
                        <v-list-item-content>
                          <v-list-item-title v-html="userNameDictionary[u]"/>
                        </v-list-item-content>
                        <v-list-item-action>
                          <v-btn @click="removeUserAt(i)" color="red" text icon>
                            <v-icon>mdi-close</v-icon>
                          </v-btn>
                        </v-list-item-action>
                      </v-list-item>
                    </v-list>
                  </div>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-btn class="ml-3" large @click="saveGroupMember">保存</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
  import { mapMutations, mapState } from 'vuex'

  export default {
    name: "group-page",
    props: {
      user: Object,
      mainColor: String,
      bgColor: String,
      isWaiting: Boolean
    },
    computed: {
      ...mapState([
        'groupList', 'userList'
      ]),
      formTitle: function () {
        return this.dialogType === 'new' ? '追加' : '編集'
      },
      userNameDictionary() {
        return this.$store.getters.userNameDictionary
      },
      userAvatarDictionary() {
        return this.$store.getters.userAvatarDictionary
      },
      filteredUserIdList() {
        let s = this.searchUserText
        let keys = ['name', 'kana', 'email']
        let temp = []
        if (s !== '') {
          temp = this.userList.filter(item => keys.some(k => item[k].toUpperCase().includes(s.toUpperCase())))
        } else {
          temp = this.userList
        }
        return temp.map(item => item.id)
      },
    },
    data() {
      return {
        showDialog: false,
        showMemberDialog: false,
        headers: [
          {
            text: 'ID',
            value: 'id'
          },
          {
            text: '組織名',
            align: 'left',
            value: 'name'
          },
          {
            text: 'メンバー数',
            align: 'left',
            value: 'members_count',
          },
          {
            text: '作成日時',
            value: 'created_at'
          },
          {
            text: '操作',
            align: 'center',
            value: 'actions',
            sortable: false
          }
        ],
        dialogType: 'new',
        editingGroupId: 0,
        editedItem: {
          name: '',
        },
        showMemberItem: {},
        members: [],
        defaultItem: {
          name: '',
        },
        searchUserText: ''
      }
    },
    created() {
      if (this.user.type < 2) {
        this.$router.push('/')
      }
    },
    methods: {
      ...mapMutations([
        'GET_GROUP_LIST', 'GET_USER_LIST'
      ]),
      editGroup(item) {
        this.dialogType = 'edit'
        this.editingGroupId = item.id
        this.editedItem = { name: item.name }
        this.showDialog = true
      },
      deleteGroup(deleteId) {
        let vm = this
        if (confirm('削除してもよろしいでしょうか？')) {
          window.setWaiting(true)
          window.api.removeGroup(deleteId).then(res => {
            vm.GET_GROUP_LIST(res.data.groups)
            Event.$emit('showAlert', '削除しました。')
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
            let editedGroup = vm.editedItem.name
            if (vm.dialogType === 'edit') {
              window.api.editGroup(vm.editedItem, vm.editingGroupId).then(res => {
                vm.GET_GROUP_LIST(res.data.groups)
                Event.$emit('showAlert', '"' + editedGroup + '"を編集しました。')
                vm.close()
                window.setWaiting(false)
              }, error => {
                Event.$emit('showAlert', error.data.message)
                window.setWaiting(false)
              })
            } else {
              window.api.addGroup(vm.editedItem).then(res => {
                vm.GET_GROUP_LIST(res.data.groups)
                Event.$emit('showAlert', '"' + editedGroup + '"を追加しました。')
                window.setWaiting(false)
                vm.close()
              }, error => {
                Event.$emit('showAlert', error.data.message)
                window.setWaiting(false)
              })
            }
          }
        })
      },
      close() {
        this.showDialog = false
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.dialogType = 'new'
        }, 300)
      },
      manageMember(item) {
        let vm = this
        vm.showMemberItem = Object.assign({}, item)
        vm.members = vm.groupMember(item.id, true)
        vm.showMemberDialog = true
      },
      groupMember(groupId, isMember) {
        if (isMember) {
          return this.userList.filter(item => item.groups.some(g => g.id === groupId)).map(item => item.id)
        } else {
          return this.userList.filter(item => !item.groups.some(g => g.id === groupId)).map(item => item.id)
        }
      },
      removeUserAt(userIndex) {
        this.members.splice(userIndex, 1)
      },
      addUser(u) {
        this.members.push(u)
      },
      selectAllUser() {
        this.members.push(...this.filteredUserIdList)
        this.checkSame()
      },
      checkSame() {
        this.members =[...new Set(this.members)]
      },
      saveGroupMember() {
        let vm = this
        window.setWaiting(true)
        window.api.editGroupMember({ members: vm.members }, vm.showMemberItem.id).then(res => {
          vm.GET_GROUP_LIST(res.data.groups)
          vm.GET_USER_LIST(res.data)
          vm.showMemberDialog = false
          vm.showMemberItem = {}
          vm.members = []
          window.setWaiting(false)
        }, error => {
          Event.$emit('showAlert', error.data.message)
          window.setWaiting(false)
        })
      },
      closeMemberDialog() {
        this.showMemberDialog = false
      }
    },
    watch: {
      showDialog(val) {
        val || this.close()
      }
    },
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

  .c-selectList {
    height: 400px;
    overflow-y: scroll;
  }

  .c-memberTitle {
    height: 72px;
  }
</style>
