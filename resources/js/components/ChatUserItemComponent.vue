<template>
  <div @click="startChat(user.id)" class="user" :class="{ active: isActive }">
    <div class="user-item">
      <div class="user-avatar">
        <img v-bind:src="'/public/avatar/' + (user.avatar !== null ? user.avatar : 'default.jpg')" />
      </div>
      <div class="user-name">
        {{ user.name}}
        <div class="time">{{ user.updated_at | moment("from", "now") }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    user: {
      type: Object,
      default: {},
    },
    isActive: false,
  },
  data() {
    return {
    };
  },
  methods: {
    startChat(receiver_id) {
      this.$emit("onToggle");
      this.$parent.$parent.switchToPrivate(receiver_id);
    },
  },
};
</script>

<style lang="scss" scoped>
.message {
  display: flex;
  color: #00b8ff;
  .message-item:not(:last-child) {
    margin-right: 5px;
  }
}
.message:not(:last-child) {
  padding-bottom: 20px;
}
.is-current-user {
  color: #a900ff;
}
</style>
