<template>
  <div class="row mainchat">
    <div class="col-chat col-user-list">
      <ChatListUser></ChatListUser>
    </div>
    <div id="div2" class="col-chat">
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
  mounted() {
    $(".col-user-list").resizable();
    var totalWidth = $(window).width() - 1;
    $(".col-user-list").resizable({
      maxWidth: $(window).width() / 2,
      handles: 'e, w',
      resize: function (event, ui) {
        var width = $(".col-user-list").width();
        if (width > totalWidth) {
          width = totalWidth;
          $(".col-user-list").css("width", width);
        }
        $("#div2").css("width", totalWidth - width);
      },
    });
  },
  created() {},
  updated: function () {},
  computed: {
    currentProperties: function () {
      if (this.current === "ChatPrivate") {
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
  .col-user-list {
    width: 25%;
  }
  #div2 {
    width: 75%;
  }
  .col-user-list,
  .col-9 {
    background-color: #fff;
  }
  .col-user-list {
    padding-right: 0px;
    padding-left: 0px;
    border-right: 1px solid #dedbdb;
  }
  .col-9 {
    padding-left: 0px;
    padding-right: 0px;
  }
}
</style>
