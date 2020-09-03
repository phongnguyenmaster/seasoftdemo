<template>
  <div class="chat">
    <div class="chat-title">
      <h1>ROOM CHAT</h1>
    </div>
    <div class="messages scroll-height">
      <div class="messages-content">
        <ChatItem
          v-for="(message, index) in list_messages"
          :beforeId="index > 0 ? list_messages[index - 1].user.id : 0"
          :isRoom="true"
          :key="index"
          :message="message"
        ></ChatItem>
      </div>
    </div>
    <div class="message-box">
      <input
        type="text"
        @keyup.enter="sendMessage"
        class="message-input form-control"
        id="txtMessage"
        placeholder="Type message..."
      />
      <button type="button" class="message-submit btn btn-success" @click="sendMessage">
        <i class="fa fa-paper-plane"></i> Send
      </button>
    </div>
  </div>
</template>
<script>
import ChatItem from "./ChatItemComponent.vue";
import ChatListUser from "./ChatListUserComponent.vue";
var socket = io.connect("http://192.168.134.1:3000");
//var socketPrivate = io.connect("http://192.168.134.1:3000/privatechat");

export default {
  components: {
    ChatItem,
  },
  data() {
    return {
      message: "",
      page: 1,
      currentHeight: 0,
      isLoadHistory: false,
      lastIdHistory: 0,
      list_messages: [],
    };
  },
  mounted() {
    var container = this.$el.querySelector(".messages");
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
    socket.on("MessagePosted", (msg) => {
      //let message = msg
      //message.user = data.user
      this.list_messages.push(msg);
    });
  },
  updated: function () {
    var container = this.$el.querySelector(".messages");
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
        .get("/loadmessageroom/" + this.lastIdHistory)
        .then((response) => {
          response.data.forEach((i) => this.list_messages.unshift(i));
          this.page++;
          this.lastIdHistory = response.data[response.data.length - 1]["id"];
        })
        .catch((error) => {
          console.log(error);
        });
    },
    sendMessage() {
      this.message = this.$el.querySelector("#txtMessage").value;
      this.$el.querySelector("#txtMessage").value = "";
      if (this.message.length == 0) {
        return;
      }
      // Append message before send to server.
      var messageContent = this.message;
      this.message = "";
      var newMessage = {
        message: messageContent,
        user: this.$root.currentUserLogin,
        created_at: Date.now(),
      };
      this.list_messages.push(newMessage);
      axios
        .post("/newMessages", {
          message: messageContent,
        })
        .then((response) => {
          socket.emit("newmessage", response.data.message);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.messages {
  height: 100%;
  overflow-y: scroll;
  .messages-content {
    display: flex;
    flex-direction: column;
  }
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
  justify-content: space-between;
  flex-direction: column;
}
/*--------------------
Chat Title
--------------------*/
.chat-title {
  flex: 0 1 45px;
  position: relative;
  z-index: 2;
  background: #fff;
  text-transform: uppercase;
  text-align: left;
  padding: 20px;
  margin-left: -15px;
  border-bottom: 1px solid #dedbdb;
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

