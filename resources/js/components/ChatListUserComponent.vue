<template>
  <div>
    <div class="chat">
      <div class="chat-title">
        <h1>CHAT</h1>
      </div>
      <div class="userlist scroll-height">
        <div class="userlist-content">
          <div @click="roomChat" class="user" :class="{ active: activeIndex === 0 }">
            <div class="user-item">
              <div class="user-avatar">
                <img src="/public/icon/chatroom.jpg" />
              </div>
              <div class="user-name hiddenwidth">
                ROOM CHAT
                <div class="time">All people</div>
              </div>
            </div>
          </div>
          <ChatUserItem
            v-for="(user, index) in list_user"
            :key="index"
            :user="user"
            :isActive="activeIndex === index + 1"
            @onToggle="onToggle(index+1)"
          ></ChatUserItem>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ChatUserItem from "./ChatUserItemComponent.vue";

export default {
  components: {
    ChatUserItem,
  },
  data() {
    return {
      message: "",
      page: 1,
      currentHeight: 0,
      lastIdHistory: 0,
      list_user: [],
      activeIndex: 0,
    };
  },
  mounted() {
    var container = this.$el.querySelector(".userlist");
    $(container).scroll(
      function (e) {
        var pos = $(e.target).scrollTop() + $(e.target).height();
        if (pos >= container.scrollHeight) {
          this.loadMessage(this.page);
        }
      }.bind(this)
    );
  },
  created() {
    this.loadMessage(this.page);
  },
  updated: function () {
  },
  methods: {
    loadMessage(page) {
      axios
        .get("/loadlistuser/" + this.page)
        .then((response) => {
          if (response.data.length > 0) {
            response.data.forEach((i) => this.list_user.push(i));
            this.page++;
            this.lastIdHistory = response.data[response.data.length - 1]["id"];
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    onToggle(index) {
      this.activeIndex = index;
    },
    roomChat() {
      this.onToggle(0);
      this.$parent.switchToRoom();
    },
  },
};
</script>

<style lang="scss" scoped>
.userlist {
  margin-top: 8px;
  margin-left: 4px;
}
.avatar {
  position: absolute;
  z-index: 1;
  top: 8px;
  left: 9px;
  border-radius: 30px;
  width: 30px;
  height: 30px;
  overflow: hidden;
  margin: 0;
  padding: 0;
  border: 2px solid rgba(255, 255, 255, 0.24);
  img {
    width: 100%;
    height: auto;
  }
}
</style>
