<template>
  <div id="app" class="sidebar-visible">
    <navigate />
    <div class="main-container flex" v-if="user.id">
      <sidebar />
      <div class="main-wrap relative-position flex">
        <transition name="next">
          <router-view class="main" />
        </transition>
      </div>
    </div>
    <div v-else class="checking-user">
      <div class="skeleton"></div>
      <div class="skeleton"></div>
    </div>
    <waiting />
  </div>
</template>

<script>
import Navigate from '~/components/global/layout/navigate'
import Sidebar from '~/components/global/layout/sidebar'

export default {
  name: 'DefaultLayout',
  components: {
    Navigate,
    Sidebar,
  },
  provide() {
    return {
      refresh: this.refresh,
    }
  },
  data() {
    return {
      isRendering: true,
    }
  },
  computed: {
    user() {
      return this.$store.state.user
    },
  },
  methods: {
    refresh() {
      this.isRendering = false
      this.$nextTick(() => {
        this.isRendering = true
      })
    },
    toggleSidebarStyle () {
      app.classList.toggle('sidebar-visible')
      app.classList.toggle('sidebar-hidden')
    },
    resizeHandler () {
      const app = document.getElementById('app')
      if (document.documentElement.offsetWidth < 980 && app.className.indexOf('sidebar-visible') >= 0) {
        this.toggleSidebarStyle()
        app.classList.remove('fix-sidebar')
        app.classList.add('fix-sidebar')
      } else if (document.documentElement.offsetWidth > 980 && app.className.indexOf('sidebar-visible') === -1) {
        app.classList.remove('fix-sidebar')
        this.toggleSidebarStyle()
      }
    }
  },
  mounted () {
    window.addEventListener('resize', this.resizeHandler)
  },
  beforeDestroy () {
    window.removeEventListener('resize', this.resizeHandler)
  }
}
</script>

<style lang="scss">
.main-container{
  flex-wrap: nowrap;
  height: 100%;
  position: fixed;
  width: 100%;
  top: 0;
}
@media screen and (min-width: 0) {
  .main-wrap {
    padding-left: 0;
    padding-right: 0;
  }
}
@media screen and (min-width: $media-min-width) {
  .main-wrap {
    padding-left: 2rem;
    padding-right: 2rem;
  }
}
.main-wrap {
  left: 0;
  top: 0;
  padding-top: 9rem;
  box-sizing: border-box;
  height: 100%;
  width: 100%;
  justify-content: center;
  transition: padding .3s;
  overflow-y: auto;
  @extend %scroll-bar;
}
.main {
  box-sizing: border-box;
  border-radius: 0.7rem;
  width: 100%;
}
.checking-user {
  width: 100%;
  height: 100%;
  position: fixed;
  padding-top: 5rem;
  background-color: #fff;
  .skeleton {
    &:empty {
      margin: 2rem auto;
      width: 50rem;
      height: 21rem; /* change height to see repeat-y behavior */
      overflow: hidden;

      background-image: linear-gradient(
          100deg,
          rgba(255, 255, 255, 0),
          rgba(255, 255, 255, 0.5) 50%,
          rgba(255, 255, 255, 0) 80%
        ),
        radial-gradient(lightgray 99%, lightgray 0),
        linear-gradient(lightgray 2rem, transparent 0),
        linear-gradient(lightgray 2rem, transparent 0),
        linear-gradient(lightgray 2rem, transparent 0),
        linear-gradient(lightgray 2rem, transparent 0);

      background-repeat: no-repeat;

      background-size: 5rem 20rem, /* circle */ 10rem 18rem,
        /* highlight */ 15rem 20rem, 35rem 20rem, 30rem 20rem, 25rem 20rem;

      background-position: -10rem 0, /* circle */ 0rem 0,
        /* highlight */ 12rem 0, 12rem 4rem, 12rem 8rem, 12rem 12rem;

      animation: shine 1.2s infinite;
    }

    @keyframes shine {
      to {
        background-position: 100% 0, 0 0, /* move highlight to right */ 12rem 0,
          12rem 4rem, 12rem 8rem, 12rem 12rem;
      }
    }
  }
}

.next-enter {
  opacity: 0;
  transform: scale3d(2, 0.5, 1);
}
.next-enter-to {
  transform: scale3d(1, 1, 1);
}
.next-enter-active,
.next-leave-active {
  position: absolute;
  transition: .5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.next-leave {
  transform: scale3d(1, 1, 1);
}
.next-leave-to {
  opacity: 0;
  transform: scale3d(2, 0.5, 1);
}
</style>
