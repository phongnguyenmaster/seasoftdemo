<template>
  <div>
    <div class="chat">
      <div class="chat-title">
        <h1 class="text-center">CHAT</h1>
      </div>
      <div class="userlist scroll-height">
        <div class="userlist-content">
          <ChatUserItem
            v-for="(user, index) in list_user"
            :key="index"
            :user="user"
            :isActive="activeIndex === index + 1"
            :isNewMessage="false"
            :ref="'item-' + user.id"
            @onToggle="onToggle(index+1, user.id)"
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
      page: 1,
      lastIdUser: 0,
      list_user: [
        {
          id: 0,
          avatar: "chatroom.jpg",
          name: "ROOM CHAT",
        },
      ],
      activeIndex: 1,
    };
  },
  mounted() {
    var container = this.$el.querySelector(".userlist");
    $(container).scroll(
      function (e) {
        var pos = $(e.target).scrollTop() + $(e.target).height();
        if (pos >= container.scrollHeight) {
          this.loadUsers(this.page);
        }
      }.bind(this)
    );
  },
  created() {
    this.loadUsers(this.page);
  },
  methods: {
    loadUsers(page) {
      axios
        .get("/loadlistuser/" + this.page)
        .then((response) => {
          if (response.data.length > 0) {
            response.data.forEach((i) => this.list_user.push(i));
            this.page++;
            this.lastIdUser = response.data[response.data.length - 1]["id"];
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    onToggle(index, userId) {
      if (this.$refs["item-" + userId].length > 0) {
        this.$refs["item-" + userId][0].isNewMessage = false;
      }
      this.activeIndex = index;
    },
    showNewMessage(userId) {
      this.$refs["item-" + userId][0].isNewMessage = true;
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
