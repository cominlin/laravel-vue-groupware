<template>
  <v-card light class="c-notificationList" width="480">
    <v-list three-line>
      <v-list-item
        active-class="not-blue"
        v-for="(n, i) in notificationList.slice(0, 10)"
        :key="'n' + i"
        :class="{ 'blue lighten-5': getColor(n) }"
        :to="n.data.url"
        @click="readNotification(n.id)"
      >
        <v-list-item-content>
          <v-list-item-title>
            <v-icon>{{ notificationTypes[getTypeKey(n)].icon }}</v-icon>
            {{ n.data.title }}
          </v-list-item-title>
          <v-list-item-sub-title>
            {{ $t(`notification.${getTypeKey(n)}`, { user: userNameDictionary[n.data.user_id] }) }}
            <v-spacer/>
            <span>{{ n.created_at | moment('timezone', user.profile.timezone, 'MM/DD HH:mm') }}</span>
          </v-list-item-sub-title>
          <v-list-item-sub-title class="caption" v-if="!isEmpty(n.data.content)" v-html="n.data.content"/>
        </v-list-item-content>
      </v-list-item>
    </v-list>
    <div class="pl-3 pb-3">
      <a class="c-link caption" href="#" @click="seeAll()">すべて見る</a>
      <a class="c-link caption ml-3" href="#" @click="readNotification('all')">すべて既読にする</a>
    </div>

  </v-card>
</template>

<script>
  import { notificationTypes } from '../static-data'
  import { mapState, mapMutations } from 'vuex'
  import { isEmpty } from '../common'

  export default {
    name: "notification-list",
    props: {
      user: Object
    },
    computed: {
      ...mapState([
        'isWaiting', 'notificationList'
      ]),
      userNameDictionary() {
        return this.$store.getters.userNameDictionary
      },
    },
    data() {
      return {
        notificationTypes: notificationTypes,
        isEmpty: isEmpty
      }
    },
    methods: {
      ...mapMutations([
        'GET_NOTIFICATION_LIST'
      ]),
      getTypeKey(notification) {
        return notification.type.split('\\')[2]
      },
      getColor(notification) {
        return isEmpty(notification.read_at)
      },
      readNotification(nid) {
        let vm = this
        window.api.setNotificationRead(nid).then(res => {
          vm.GET_NOTIFICATION_LIST(res.data.notifications)
        })
        this.$emit('close')
      },
      seeAll() {
        this.$emit('close')
        this.$router.push('/notification')
      }
    }
  }
</script>

<style scoped>
  .c-notificationList {
    position: absolute;
    top: 45px;
    right: -10px;
  }
</style>
