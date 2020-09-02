<template>
	<div class="user" :class="{'is-current-user': $root.currentUserLogin.id === user.id}">
        <div class="user-item" @click="startChat(user.id)">
         <div class="user-avatar">
            <img v-bind:src="'/public/avatar/' + (user.avatar !== null ? user.avatar : 'default.jpg')" />
		</div>
		<div class="user-name">
			{{ user.name}}
		</div>
        </div>
	</div>
</template>

<script>
	export default {
        props: {
          user: {
            type: Object,
            default: {}
          }
        },
        methods: {
            startChat(receiver_id) {
                this.$parent.$parent.switchToPrivate(receiver_id);
            }
        }
    }
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
