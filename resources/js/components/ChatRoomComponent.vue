<template>
  <div class="chat">
    <div class="chat-title">
      <ChatUserItem :isActive="false" :user="userReceiverInfo"></ChatUserItem>
    </div>
    <div class="messages show scroll-height">
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
      <ChatInput></ChatInput>
    </div>
  </div>
</template>
<script>
import ChatItem from "./ChatItemComponent.vue";
import ChatUserItem from "./ChatUserItemComponent.vue";
import ChatInput from "./ChatInputComponent.vue";

export default {
  components: {
    ChatItem,
    ChatUserItem,
    ChatInput,
  },
  data() {
    return {
      message: "",
      page: 1,
      currentHeight: 0,
      isLoadHistory: false,
      lastIdHistory: 0,
      list_messages: [],
      userReceiverInfo: { avatar: "chatroom.jpg", name: "ROOM CHAT", id: 0 },
      socket: io.connect($("#socketUrl").val()),
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
    this.socket.on("newmessage", (id) => {
      this.$parent.showNewMessage(0);
      this.getMessageContent(id);
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
    getMessageContent(id) {
      axios
        .post("/getMessageContent", { id: id + '333' })
        .then((response) => {
          if (response.data) {
            this.list_messages.push(response.data);
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    loadMessage(page) {
      this.isLoadHistory = true;
      axios
        .get("/loadmessageroom/" + this.lastIdHistory)
        .then((response) => {
          if (response.data.length > 0) {
            response.data.forEach((i) => this.list_messages.unshift(i));
            this.page++;
            this.lastIdHistory = response.data[response.data.length - 1]["id"];
          } else {
            this.isLoadHistory = false;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    sendMessage(message) {
      if (message.length == 0) {
        return;
      }
      // Append message before send to server.
      var messageContent = message;
      var newMessage = {
        message: message,
        user: this.$root.currentUserLogin,
        created_at: Date.now(),
      };
      this.list_messages.push(newMessage);
      axios
        .post("/newMessages", {
          message: newMessage.message,
        })
        .then((response) => {
          this.socket.emit("newmessage", response.data.message.id);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
