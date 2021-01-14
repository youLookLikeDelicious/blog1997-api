<template>
  <div class="user-info">
    <img
      class="avatar"
      :src="user.avatar"
      :alt="alt"
      onerror="this.src='/vue/image/avatar.svg';this.onerror=null"
    />
    <span v-if="user.name">{{ user.name }}</span>
  </div>
</template>

<script>
export default {
  name: 'Avatar',
  props: {
    user: {
      type: Object,
      default() {
        return {
          avatar: '',
          name: '',
        }
      },
    },
    alt: {
      type: String,
      default() {
        return ''
      },
    },
  },
}
</script>

<style lang="scss">
.user-info {
  display: flex;
  align-items: center;
  padding-right: 2rem;
}
.avatar {
  $size: 3.5rem;
  width: $size;
  height: $size;
  line-height: $size;
  border-radius: $size;
  text-align: center;
  margin-right: 0.7rem;
  i {
    font-size: 3rem;
  }
}
</style>