<template>
  <div>
    <div class="chat">
      <div class="chat-title">
        <h1>Users List</h1>
      </div>
      <div class="userlist scroll-height">
        <div class="userlist-content">
          <div @click="roomChat" class="user" :class="{ active: activeIndex === null }">
            <div class="user-item">
              <div class="user-avatar">
                <img src="/public/icon/chatroom.jpg" />
              </div>
              <div class="user-name">
                ROOM CHAT
                <div class="time">All people</div>
              </div>
            </div>
          </div>
          <ChatUserItem
            v-for="(user, index) in list_user"
            :key="index"
            :user="user"
            :isActive="activeIndex === index"
            @onToggle="onToggle(index)"
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
      isLoadHistory: false,
      lastIdHistory: 0,
      list_user: [],
      activeIndex: null,
    };
  },
  mounted() {
    var container = this.$el.querySelector(".userlist");
    $(container).scroll(
      function (e) {
        var pos = $(e.target).scrollTop();
        if (pos <= 0) {
          this.currentHeight = container.scrollHeight;
          this.loadMessage(this.page);
        }
      }.bind(this)
    );
  },
  created() {
    this.loadMessage(this.page);
  },
  updated: function () {
    var container = this.$el.querySelector(".userlist");
    if (!this.isLoadHistory) {
      container.scrollTop = container.scrollHeight;
    } else {
      container.scrollTop =
        $(container).children(0).height() - this.currentHeight;
      this.isLoadHistory = false;
    }
  },
  methods: {
    loadMessage(page) {
      this.isLoadHistory = true;
      axios
        .get("/loadlistuser/" + this.page)
        .then((response) => {
          response.data.forEach((i) => this.list_user.push(i));
          this.page++;
          this.lastIdHistory = response.data[response.data.length - 1]["id"];
        })
        .catch((error) => {
          console.log(error);
        });
    },
    onToggle(index) {
      if (this.activeIndex === index) {
        this.activeIndex = null;
      } else {
        this.activeIndex = index;
      }
    },
    roomChat() {
      this.onToggle(null);
      this.$parent.switchToRoom();
    },
  },
};
</script>

<style lang="scss" scoped>
.messages {
  height: 100%;
  overflow-y: scroll;
  padding: 0 20px;
}
/*--------------------
Body
--------------------*/
/*--------------------
Chat
--------------------*/
.chat {
  height: calc(100vh - 55px);
  max-height: 700px;
  z-index: 2;
  overflow: hidden;
  background: white;
  display: flex;
  flex-direction: column;
}
/*--------------------
Chat Title
--------------------*/

.userlist {
  margin-top: 8px;
}
.chat-title {
  flex: 0 1 45px;
  position: relative;
  z-index: 2;
  background: #fff;
  text-transform: uppercase;
  text-align: left;
  padding: 10px 0px 10px 0px;

  h1,
  h2 {
    font-weight: bold;
    font-size: 16px;
    margin: 0;
    padding: 0;
  }
  h2 {
    color: rgba(255, 255, 255, 0.5);
    font-size: 8px;
    letter-spacing: 1px;
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
}
/*--------------------
Message Box
--------------------*/
.message-box {
  flex: 0 1 40px;
  width: 100%;
  padding: 10px;
  position: relative;

  & textarea:focus:-webkit-placeholder {
    color: transparent;
  }

  & .message-submit {
    z-index: 1;
    top: 9px;
    right: 10px;
    transition: background 0.2s ease;
    position: absolute;
    &:hover {
      background: #1d7745;
    }
  }
}
</style>
