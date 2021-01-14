<template>
  <!-- 有两个主题 theme="box|inline" -->
  <a
    v-if="theme === 'box'"
    @click.stop.prevent
    @click="submit"
    :class="['btn-enable', { 'btn-disable': !allowSubmit }]"
    href="/"
    >提交</a
  >
  <a
    v-else
    href="/"
    @click.prevent
    @click="submit"
    :class="[
      'link-btn-primary',
      'icofont-upload-alt',
      { 'btn-disable': !allowSubmit },
    ]"
    >提交</a
  >
</template>

<script>
export default {
  name: 'SubmitBtn',
  props: {
    allowSubmit: {
      default() {
        return true
      },
    },
    theme: {
      type: String,
      default() {
        return 'box'
      },
    },
  },
  methods: {
    submit() {
      if (!this.allowSubmit) {
        return
      }
      this.$emit('submit')
    },
  },
}
</script>

<style>
</style>