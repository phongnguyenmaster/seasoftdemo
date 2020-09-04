<template>
  <div
    class="message"
    :class="($root.currentUserLogin.id !== message.user.id ? 'reply-chat': 'my-chat')"
  >
    <div
      class="img"
      v-if="(beforeId != message.user.id) && ($root.currentUserLogin.id !== message.user.id)"
    >
      <img
        v-bind:src="'/public/avatar/' + (message.user.avatar !== null ? message.user.avatar : 'default.jpg')"
      />
    </div>
    <div class="content">
      <div
        class="name"
        v-if="isRoom && (beforeId != message.user.id) && ($root.currentUserLogin.id !== message.user.id)"
      >{{ message.user.name }}</div>
      <div class="messagecontent">{{ message.message }}</div>
      <div class="timestamp">{{ message.created_at | moment("from", "now") }}</div>
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
    beforeId: 0,
    isRoom: false,
  },
};
</script>

<style lang="scss" scoped>
.message {
  position: relative;
  .content {
    padding: 5px 8px 6px;
    margin-top: 2px;
    margin-bottom: 2px;
    border-radius: 0px 8px 8px 8px;
    word-break: break-word;
    .messagecontent {
      font-size: 15px;
      color: #222222;
    }
    .timestamp,
    .name {
      color: rgb(182 182 182);
      font-size: 12px;
    }
  }
}
.reply-chat {
  .img {
    position: absolute;
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
    background-color: white;
    float: left;
    margin-left: 45px;
  }
}
.my-chat {
  .content {
    background-color: #dae9ff;
    float: right;
    .timestamp {
      color: rgba(0, 0, 0, 0.5);
    }
  }
}
</style>
