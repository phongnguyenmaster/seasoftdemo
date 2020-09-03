<template>
  <div
    class="message"
    :class="($root.currentUserLogin.id !== message.user.id ? 'reply-chat': 'my-chat')"
  >
    <div class="img">
      <img
        v-if="$root.currentUserLogin.id !== message.user.id"
        v-bind:src="'/public/avatar/' + (message.user.avatar !== null ? message.user.avatar : 'default.jpg')"
      />
    </div>
    <div class="content">
      {{ message.message }}
      <div class="timestamp">{{ message.created_at }}</div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    message: {
      type: Object,
      default: {},
    },
  },
};
</script>

<style lang="scss" scoped>
.reply-chat {
  .img {
    img {
      width: 40px;
      height: 40px;
      border-radius: 100%;
      float: left;
      border: 1px solid #f1f0f0;
      margin: 1px;
    }
  }
  .content {
    background-color: #f1f0f0;
    float: left;
    padding: 5px 8px 6px;
    color: #4b4f56;
    margin-right: 30px;
    margin-top: 2px;
    margin-bottom: 2px;
    border-radius: 0px 8px 8px 8px;
    font-size: 12px;
    word-break: break-word;
  }
}
.my-chat {
  .content {
    background-color: #4080ff;
    float: right;
    padding: 5px 8px 6px;
    color: white;
    margin-left: 30px;
    margin-top: 2px;
    margin-bottom: 2px;
    border-radius: 8px 0px 8px 8px;
    font-size: 12px;
    word-break: break-word;
  }
}
.message:not(:last-child) {
  padding-bottom: 20px;
}
.is-current-user {
  color: #a900ff;
}
</style>
