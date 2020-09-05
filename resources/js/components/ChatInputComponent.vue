<template>
  <div>
    <input
      type="text"
      v-model="message"
      @keyup.enter="sendMessage"
      class="message-input form-control"
      placeholder="Type message..."
    />
    <button type="button" class="message-submit btn btn-success" @click="sendMessage">
      <i class="fa fa-paper-plane"></i> Send
    </button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      message: "",
    };
  },
  methods: {
    sendMessage() {
      this.$parent.sendMessage(this.message);
      this.message = "";
    },
  },
};
</script>
