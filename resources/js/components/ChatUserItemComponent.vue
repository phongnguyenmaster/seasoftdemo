<template>
  <div
    @click="startChat(user.id)"
    class="user"
    :class="{ active: isActive, newmessage: isNewMessage }"
  >
    <div class="user-item">
      <div class="user-avatar">
        <img v-bind:src="'/public/avatar/' + (user.avatar !== null ? user.avatar : 'default.jpg')" />
      </div>
      <div class="user-name hiddenwidth">
        {{ user.name}}
        <div class="time">{{ $moment(user.updated_at).fromNow() }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    user: {
      type: Object,
      default: () => ({}),
    },
    isActive: false,
  },
  data() {
    return {
      isNewMessage: false,
    };
  },
  methods: {
    startChat(receiver_id) {
      this.$emit("onToggle");
      if (receiver_id == 0) {
        this.$parent.$parent.switchToRoom();
      } else {
        this.$parent.$parent.switchToPrivate(receiver_id);
      }
    },
  },
};
</script>
