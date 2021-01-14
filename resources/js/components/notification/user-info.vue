<template>
  <div class="notification-user-info-wrapper">
    <img
      class="avatar"
      :src="user.avatar"
      onerror="this.src='/vue/image/avatar.svg';this.onerror=null"
    />
    <span>{{ user.name }}</span>
  </div>
</template>

<script>
export default {
  name: "UserInfo",
  props: {
    user: {
      type: Object,
      default() {
        return {};
      },
    },
  },
};
</script>

<style lang="scss">
.notification-user-info-wrapper {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  .avatar{
      $size: 2.5rem;
      width: $size;
      height: $size;
      border-radius: $size;
  }
}
</style>