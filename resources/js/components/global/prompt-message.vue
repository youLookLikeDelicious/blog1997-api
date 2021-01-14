<template>
  <transition-group @enter="enter" tag="div">
    <div
      v-for="({ message, status, symbol }, index) in prompt"
      :data-index="index"
      :key="symbol"
      :class="[
        'prompt-message',
        { 'message-success': status, 'message-error': !status },
      ]"
    >
      <i v-if="status" class="tag">✔</i>
      <i v-if="!status" class="tag">✘</i>
      {{ message }}
    </div>
  </transition-group>
</template>

<script>
export default {
  name: "PromptMessage",
  props: {
    prompt: {
      type: Array,
      default() {
        return []
      }
    }
  },
  methods: {
    // 启动开启动画，三秒之后将消息框的高度设为0，然后清空vux的消息状态
    enter(el, done) {
      const index = parseInt(el.dataset.index);
      el.style.top = this.calculateTop(index)
      this.$animate(
        el,
        { top: el.offsetTop + el.offsetHeight + 'px', opacity: 1, duration: 256 },
        () => {
          window.setTimeout(() => this.leave(el), 5000);
          done();
        }
      );
    },
    leave(el) {
      const messageList = this.getMessageList()

      for (let i = 0, len = messageList.length; i < len; i++) {
        this.$animate(
          messageList[i],
          {
            top: messageList[i].offsetTop - messageList[i].offsetHeight + 'px',
            opacity: i? 1 : 0,
            duration: 256,
          },
        );
      }
      window.setTimeout(() => {this.$emit('shift-message')}, 260)
    },
    /**
     * 计算top
     * 
     * @param {int} index
     * @return {void}
     */
    calculateTop (index) {
      if (index === 0) {
        return '4rem'
      }

      const messageList = this.getMessageList()
      const siblingEl = messageList[index - 1]
      return siblingEl.offsetTop + 21 + 'px'
    },
    getMessageList () {
      return this.$el.querySelectorAll('.prompt-message')
    }
  },
};
</script>

<style lang="scss">
.prompt-message {
  display: block;
  position: fixed;
  width: fit-content;
  opacity: 0;
  min-width: 35rem;
  left: 50%;
  z-index: 10003;
  padding: 1.7rem 2.5rem;
  transform: translateX(-50%);
  border-radius: 0.7rem;
  border: 0.1rem solid #c6c6c6;
  .tag {
    margin-right: 1.5rem;
  }
}
.message-success {
  background-color: #f0f9eb;
  border: 0.1rem solid #bcf0a0;
  color: #82ca5e;
}
.message-error {
  background-color: #fde2e2;
  border: 0.1rem solid #f8cfcf;
  color: #f38585;
}
</style>
