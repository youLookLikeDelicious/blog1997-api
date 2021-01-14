<template>
  <header class="navigate">
    <div class="logo">
      <div>blog1997</div>
      <a href="/" class="menu relative-position" @click.prevent="toggleSideBar">
        <span ref="menu" class="hamburger-menu hamburger-menu-on absolute-position"></span>
      </a>
    </div>
    <div class="user-info-wrapper">
      <div class="user-info" v-if="user.id">
        <avatar :user="user" :alt="user.name" />
        <a href="/" @click.stop.prevent @click="logout" class="icofont-logout"></a>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: "Navigate",
  computed: {
    user() {
      return this.$store.state.user;
    },
    avatar () {
      return this.user.avatar
    }
  },
  methods: {
    logout() {
      this.$axios.post("/oauth/logout", { id: this.user.id }).then((response) => {
        this.$store.commit("user/setUser", {});
      });
    },
    /**
     * 隐藏或显示侧边栏
     */
    toggleSideBar () {
      const app = document.getElementById('app')
      if (document.documentElement.offsetWidth > 980) {
        app.classList.toggle('fix-sidebar')
      }
      app.classList.toggle('sidebar-hidden')
      app.classList.toggle('sidebar-visible')
      this.$refs.menu.classList.toggle('hamburger-menu-on')
      this.$refs.menu.classList.toggle('hamburger-menu-off')
    }
  },
};
</script>

<style lang="scss">
.navigate {
  top: 0;
  left: 0;
  width: 100%;
  height: 6rem;
  z-index: 99999;
  display: flex;
  position: fixed;
  justify-content: space-between;
  background-image: linear-gradient(120deg, #a6c0fe 0%, #f68084 100%);
  box-shadow: 0 5px 5px -3px rgba(0, 0, 0, 0.2),
    0 8px 10px 1px rgba(0, 0, 0, 0.04), 0 3px 14px 2px rgba(0, 0, 0, 0.12);
  .logo {
    color: #fff;
    display: flex;
    width: 28rem;
    padding: 0 1.5rem;
    line-height: 6rem;
    font-weight: bold;
    align-items: center;
    box-sizing: border-box;
    background: rgba(250, 251, 252, 0.1);
    justify-content: space-between;
    a {
      font-size: 2.5rem;
      line-height: 6rem;
    }
  }
  .menu{
    display: inline-block;
    width: 3rem;
    height: 3rem;
  }
}
.user-info-wrapper{
    display: flex;
    font-weight: bold;
    color: #555;
    align-items: center;
    justify-content: flex-end;
    span{
        margin: 0 .7rem;
    }
    .icofont-logout{
        font-weight: bolder;
    }
}
.hamburger-menu-off{
  &::after{
    transform: rotate(45deg) translateY(-1.2rem);
  }
  &::before{
    transform: rotate(-45deg) translateY(1.2rem);
  }
  background-color: rgba(255, 255, 255, 0);
}
.hamburger-menu-on{
  &::after{
    transform: rotate(0) translateY(0);
  }
  &::before{
    transform: rotate(0) translateY(0);
  }
  background-color: rgba(255, 255, 255, 1);
}
.hamburger-menu{
  width: 3rem;
  height: .3rem;
  display: inline-block;
  border-radius: .3rem;
  top: 1.3rem;
  transition: background-color .3s;
  border-radius: .3rem;
  &::before{
    content: '';
    width: 3rem;
    height: .3rem;
    display: inline-block;
    background-color: #fff;
    position: absolute;
    top: -.9rem;
    border-radius: .3rem;
    transition: transform .3s
  }
  &::after{
    content: '';
    width: 3rem;
    height: .3rem;
    display: inline-block;
    background-color: #fff;
    position: absolute;
    top: .9rem;
    border-radius: .3rem;
    transition: transform .3s
  }
}
</style>