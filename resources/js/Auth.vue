<template>
  <div id="app" class="auth-page">
    <router-view></router-view>

    <div class="footer-info">
      <hr />
      <p>
        图片来自
        <a target="__blank" href="https://pixabay.com/">pixabay</a>
      </p>
      <p>&copy; www.blog1997.com</p>
    </div>
    <prompt-message :prompt="prompt" />
    <waiting />
  </div>
</template>

<script>
export default {
  name: "Auth",
  computed: {
    prompt () {
      return this.$store.state.globalState.prompt
    }
  }
};
</script>

<style lang="scss">
.footer-info {
  margin-top: 0rem;
  text-align: center;
  color: #f7f7f7;
  hr {
    width: 39rem;
    margin: 1rem auto;
  }
}
.auth-page {
  background-image: url("/vue/image/background.jpg");
  background-repeat: no-repeat;
  background-size: cover;
  height: 100%;
  width: 100%;
  position: absolute;
}
.auth-table {
  @extend %edit-table;
  td {
    text-align: center !important;
  }
  i {
    color: #666;
    font-size: 1.6rem;
    top: 0.3rem;
    left: .3rem;
    position: absolute;
  }
  div {
    width: 21rem;
    margin: 0 auto;
  }
  input {
    text-indent: 2rem;
  }
  .hr-wrap {
    width: 21rem;
  }
}

.form-box {
  max-width: 65rem;
  width: 80%;
  margin: 12rem auto 12rem auto;
  padding: 2rem 0;
  background-color: #fff;
  border-radius: 0.3rem;
  box-shadow: 0 5px 5px -3px rgba(200, 200, 200, 0.2),
    0 8px 10px 1px rgba(200, 200, 200, 0.14),
    0 3px 14px 2px rgba(200, 200, 200, 0.12);
  -webkit-box-reflect: below 0.7rem
    linear-gradient(transparent, transparent, #000005);
  -ms-box-reflect: below 0.7rem
    linear-gradient(transparent, transparent, #000005);
  @media screen {
  }
  header {
    font-size: 2.3rem;
    font-weight: bolder;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    background-image: linear-gradient(to right bottom, #00c6fb, #005bea);
    text-align: center;
  }
  
  .register-btn {
    color: #fff !important;
    letter-spacing: 1rem;
    box-sizing: border-box;
    width: 21rem;
    height: 3.5rem;
    padding: 0;
    line-height: 3.5rem;
    border-radius: 0.3rem;
    display: inline-block;
  }
}
.auth-btn {
  color: #fff !important;
  width: 21rem;
  height: 3.5rem;
  line-height: 3.5rem;
  border-radius: 0.3rem;
  display: inline-block;
  box-sizing: border-box;
  background-color: #6cacff;
  transition: background-color 0.5s;
  &:hover {
    background-color: #5e9eff;
  }
}
.auth-btn-disable {
  background-color: #bbbbbb;
  &:hover {
    background-color: #a7a7a8;
  }
}
</style>