<template>
  <div class="error-page flex">
    <div class="error-page-image permision-deny"></div>
    <div class="error-page-content">
      <div>
        <p><span> 401 | permission deny </span></p>
        <p>你没有相关的权限，访问当前的页面</p>
        <p>
            <router-link to="/">返回首页</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "PermissionDeny",
  layout: "pure",
};
</script>

<style lang="scss">
.permision-deny{
  background-image: url("/svg/403.svg");
}
</style>