<template>
  <div>
    <component :is="layout" />
    <prompt-message @shift-message="shiftMessage" :prompt="prompt" />
  </div>
</template>

<script>
import DefaultLayout from '~/layout/default'
const layouts = {
  default: DefaultLayout,
}

const importAll = (r) => {
  const pattern = /([\w-_]+).vue/
  r.keys().forEach((key) => {
    const result = key.match(pattern)
    if (result && !layouts[result[1]]) {
      layouts[result[1]] = r(key).default
    }
  })
}
importAll(require.context('~/layout', false))

export default {
  name: 'Apps',
  data() {
    return {
      layoutName: 'default',
      observer: '',
    }
  },
  computed: {
    layout() {
      if (layouts[this.layoutName]) {
        return layouts[this.layoutName]
      }
      console.error('未定义的layout')
      return layouts.default
    },
    prompt() {
      return this.$store.state.globalState.prompt
    },
  },
  watch: {
    '$route.meta': {
      handler() {
        if (this.$route.meta.layout) {
          this.layoutName = this.$route.meta.layout
        }
      },
      immediate: true,
    },
    layout() {
      this.observe()
    },
  },
  methods: {
    /**
     * 从栈顶移除通知消息
     *
     * @return {void}
     */
    shiftMessage() {
      this.$store.commit('globalState/shiftPromptMessage')
    },
    observe() {
      this.$nextTick(() => {
        if (this.observer) {
          this.observer.disconnect()
          this.observer = ''
        }
        const app = document.getElementById('app')
        const config = { attributes: false, childList: true, subtree: true }

        const callback = (mutationsList, observer) => {
          for (const mutation of mutationsList) {
            if (mutation.type === 'childList') {
              this.$lazy()
            }
          }
        }
        const observer = new MutationObserver(callback)
        observer.observe(app, config)
        this.observer = observer
      })
    },
  },
  mounted() {
    this.observe()
  },
  beforeDestroy() {
    this.observer.disconnect()
    this.observer = ''
  },
}
</script>

<style lang="scss">
</style>
