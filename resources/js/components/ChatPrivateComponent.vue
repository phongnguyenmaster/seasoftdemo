<template>
  <div class="chat">
    <div class="chat-title">
      <ChatUserItem :isActive="false" :user="userReceiverInfo"></ChatUserItem>
    </div>
    <div class="messages scroll-height" :class="animation ? 'show' : ''">
      <div class="messages-content">
        <ChatItem
          v-for="(message, index) in list_messages"
          :beforeId="index > 0 ? list_messages[index - 1].user.id : 0"
          :isRoom="false"
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
  props: {
    receiver_id: 0,
  },
  data() {
    return {
      message: "",
      page: 1,
      currentHeight: 0,
      isLoadHistory: false,
      lastIdHistory: 0,
      list_messages: [],
      privateKey: 0,
      animation: 0,
      userReceiverInfo: { avatar: "default.jpg" },
      socket: io.connect($("#socketUrl").val() + "/privatechat"),
    };
  },
  mounted() {
    var container = this.$el.querySelector(".messages");
    $(container).scroll(
      function (e) {
        var pos = $(e.target).scrollTop();
        if (pos <= 0) {
          this.currentHeight = container.scrollHeight;
          if (this.lastIdHistory != 0) {
            this.loadMessage(this.page);
          }
        }
      }.bind(this)
    );
  },
  created() {
    this.changeReceiver();
    this.socket.on("MessagePosted", (msg) => {
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
  watch: {
    receiver_id: function (newVal, oldVal) {
      this.changeReceiver();
    },
  },
  methods: {
    changeReceiver() {
      this.loadPrivateKey();
      this.list_messages = [];
      this.lastIdHistory = 0;
      this.$root.getUserInfo(
        this.receiver_id,
        function (data) {
          this.userReceiverInfo = data;
          this.animation = 1;
        }.bind(this)
      );
      this.animation = 0;
    },
    loadPrivateKey() {
      if (this.privateKey > 0) {
        this.socket.emit("unregisterregister", this.privateKey);
      }
      axios
        .get("/getPrivateKey/" + this.receiver_id)
        .then((response) => {
          if (response.data.status == 1) {
            this.privateKey = response.data.private_key;
            this.loadMessage();
            this.socket.emit("register", this.privateKey);
          } else {
            alert(response.data.message);
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    loadMessage(page) {
      this.isLoadHistory = true;
      axios
        .post("/loadMessagePrivate", {
          privateKey: this.privateKey,
          lastIdHistory: this.lastIdHistory,
        })
        .then((response) => {
          if (response.data.length > 0) {
            response.data.forEach((i) => this.list_messages.unshift(i));
            this.lastIdHistory = response.data[response.data.length - 1]["id"];
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
      var newMessage = {
        message: message,
        user: this.$root.currentUserLogin,
        created_at: Date.now(),
      };
      this.list_messages.push(newMessage);
      axios
        .post("/newMessages", {
          message: newMessage.message,
          privateKey: this.privateKey,
        })
        .then((response) => {
          response.data.message.privateKey = this.privateKey;
          this.socket.emit("private_chat", response.data.message);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
