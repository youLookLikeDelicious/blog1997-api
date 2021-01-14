<template>
  <div>
    <transition name="next">
      <router-view></router-view>
    </transition>
  </div>
</template>

<script>
export default {
  name: "Message",
};
</script>

<style>
</style>