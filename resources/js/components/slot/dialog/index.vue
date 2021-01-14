<template>
  <!--<dialog :titl="''"></dialog>-->
  <div class="dialog-wrap">
    <transition appear @enter="enter" @leave="leave">
      <div
        v-if="showDialog"
        class="dialog relative-position"
        :style="{
          width: computedWidth,
          height: computedHeight,
          'max-width': '80%',
          'max-height': '90%',
        }"
      >
        <header>
          <span>{{ title }}</span>
          <a href="/" title="关闭" @click.stop.prevent @click="close">✘</a>
        </header>
        <!-- 对话框内容部分 -->
        <div class="dialog-slot-container">
          <slot />
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'VDialog',
  props: {
    title: {
      type: String,
      default() {
        return 'title'
      },
    },
    width: {
      type: String,
      default() {
        return '93%'
      },
    },
    height: {
      type: String,
      default() {
        return '17rem'
      },
    },
  },
  data() {
    return {
      showDialog: true,
      animateDuration: 320,
    }
  },
  computed: {
    computedWidth() {
      const width = parseInt(this.width)
      return width / 2 + this.width.replace(width, '')
    },
    computedHeight() {
      const height = parseInt(this.height)
      return height / 2 + this.height.replace(height, '')
    },
  },
  methods: {
    /**
     * 载入动画
     * @param el
     * @param done
     */
    enter(el, done) {
      this.$animate(
        el,
        {
          height: this.height,
          width: this.width,
          marginTop: '12rem',
          opacity: 1,
          easing: 'bezier(0.6, 1.3)',
          duration: this.animateDuration,
        },
        done
      )
    },
    /**
     * 离开动画
     * @param el
     * @param done
     */
    leave(el, done) {
      this.$animate(
        el,
        {
          height: this.computedHeight,
          width: this.computedWidth,
          opacity: 0,
          marginTop: '24rem',
          easing: 'bezier(0.3, 0.7)',
          duration: this.animateDuration,
        },
        done
      )
    },
    /**
     * 关闭该窗口
     */
    close() {
      this.showDialog = false
      window.setTimeout(() => {
        this.$store.commit('globalState/showDialog', false)
        this.showDialog = true
        this.$emit('close')
      }, this.animateDuration + 20)
    },
  },
}
</script>

<style lang="scss">
.dialog-wrap {
  position: fixed;
  height: 100%;
  width: 100%;
  top: 0;
  left: 0;
  box-sizing: border-box;
  overflow: hidden;
  z-index: 1000;
  background-color: rgba(0, 0, 0, 0.3);
  animation-duration: 512ms;
  header {
    width: 100%;
    height: 4rem;
    display: flex;
    padding: 0 0.7rem;
    align-items: center;
    box-sizing: border-box;
    border-bottom: 0.1rem solid #c6c6c6;
    justify-content: space-between;
    .little-prompt {
      @extend %little-prompt;
    }
  }
  .dialog {
    opacity: 0;
    margin: 35rem auto 0 auto;
    background-color: #fff;
    border: 0.1rem solid #c6c6c6;
    border-radius: 0.5rem;
    padding: 0 1.7rem 7rem 1.7rem;
    box-shadow: 0.2rem 0.3rem 1rem #666;
    overflow: hidden;
    box-sizing: border-box;
  }
  .dialog-slot-container {
    overflow-x: hidden;
    overflow-y: auto;
    height: 100%;
    &::-webkit-scrollbar {
      width: 0.3rem;
      height: 100%;
    }

    /* Track */
    &::-webkit-scrollbar-track {
      transition: all 1s ease-out;
      box-shadow: inset 0 0 5px #f6f6f6;
      background-color: inherit;
    }

    /* Handle */
    &::-webkit-scrollbar-thumb {
      @include compatible-style(
        '',
        (
          'background-image': linear-gradient(to bottom right, #e6e6e6, #cecece),
        )
      );
      border-radius: 10px;
    }
    /* Handle on hover */
    &::-webkit-scrollbar-thumb:hover {
      @include compatible-style(
        '',
        (
          'background-image': linear-gradient(to bottom right, #e6e6e6, #999),
        )
      );
    }
  }
}
</style>
