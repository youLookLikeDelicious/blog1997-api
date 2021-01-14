<template>
  <div class="error-page flex">
    <div class="error-page-image not-found"></div>
    <div class="error-page-content">
      <div>
        <p><span> 404 | Not Found </span></p>
        <p></p>
        <p>
            <router-link to="/">返回首页</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound'
}
</script>

<style lang="scss">
.not-found{
  background-image: url("/svg/404.svg");
}
</style>