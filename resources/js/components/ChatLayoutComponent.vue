<template>
  <div class="row mainchat">
    <div id="col-user-list">
      <ChatListUser :ref="'listUser'"></ChatListUser>
    </div>
    <div id="col-main-chat">
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
  created() {
    this.$parent.getCurrentUserLogin();
  },
  mounted() {
    var totalWidth = $(".mainchat").width() - 1;
    $("#col-user-list").resizable({
      minWidth: 86,
      handles: "e, w",
      resize: function (event, ui) {
        var width = $("#col-user-list").width();
        $("#col-main-chat").css("width", totalWidth - width);
      },
    });
    // Reset resize for list user
    $(window).resize(function (e) {
      if (e.target == window) {
        // Get width of current screen
        totalWidth = $(window).width() - 1;
        $("#col-user-list").css("width", "");
        $("#col-main-chat").css("width", "");
      }
    });
  },
  computed: {
    currentProperties: function () {
      if (this.current === "ChatPrivate") {
        return { receiver_id: this.receiver_id };
      }
    },
  },
  methods: {
    showNewMessage(userId) {
        this.$refs['listUser'].showNewMessage(userId);
    },
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
  overflow: hidden;
  width: 100vw;
  height: calc(100vh - 55px);
  #col-user-list {
    width: 25%;
  }
  #col-main-chat {
    width: 75%;
  }
  #col-user-list,
  #col-main-chat {
    background-color: #fff;
  }
  #col-user-list {
    padding-right: 0px;
    padding-left: 0px;
    border-right: 1px solid #dedbdb;
  }
  #col-main-chat {
    padding-left: 0px;
    padding-right: 0px;
  }
}
</style>
