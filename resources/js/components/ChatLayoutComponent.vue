<template>
  <div class="row mainchat">
    <div class="col-3">
      <button class="btn btn-light" @click="switchToRoom">Room chat</button>
      <ChatListUser></ChatListUser>
    </div>
    <div class="col-9">
      <component :is="current" v-bind="currentProperties"></component>
    </div>
  </div>
</template>

<script>
import ChatListUser from "./ChatListUserComponent.vue";
import ChatRoom from "./ChatRoomComponent.vue";
import ChatPrivate from "./ChatPrivateComponent.vue";

export default {
  components: {
    ChatListUser,
    ChatRoom,
    ChatPrivate,
  },
  data() {
    return {
      current: "ChatRoom",
      receiver_id: 0,
    };
  },
  mounted() {},
  created() {},
  updated: function () {},
  computed: {
    currentProperties: function () {
      if (this.current === "ChatPrivate") {
        alert(this.receiver_id);
        return { receiver_id: this.receiver_id };
      }
    },
  },
  methods: {
    switchToRoom() {
      this.current = "ChatRoom";
    },
    switchToPrivate(receiver_id) {
      this.current = "ChatPrivate";
      this.receiver_id = receiver_id;
    },
  },
};
</script>

<style lang="scss" scoped>
.mainchat {
  .col-3,
  .col-9 {
    background-color: #fff;
  }
  .col-3 {
    padding-right: 0px;
    border-right: 1px solid #dedbdb;
  }
  .col-9 {
    padding-left: 0px;
  }
}
</style>
