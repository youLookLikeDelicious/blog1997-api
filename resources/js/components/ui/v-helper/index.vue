<template>
  <span class="relative-position" @mouseenter="showContent = true" @mouseleave="showContent = false">
    <a href="/" @click.prevent>
      <i class="help-tag"></i>
      {{ title }}
    </a>
    <div v-if="showContent" class="helper-content absolute-position" v-html="content"></div>
  </span>
</template>

<script>
export default {
  name: 'VHelper',
  props: {
    title: {
      type: String,
      default() {
        return ''
      },
    },
    content: {
      type: String,
      default () {
        return ''
      }
    }
  },
  data() {
    return {
      showContent: false
    }
  }
}
</script>

<style lang="scss">
.helper-content{
  background-color: #fff;
  border: .1rem solid #d8d8d8;
  text-align: left;
  width: 18rem;
  word-break: break-all;
  padding: .5rem .7rem;
  border-radius: .3rem;
  white-space: normal;
  line-height: 2.3rem;
  box-shadow: 0 2px 3px 0 rgba(0,0,0,.12);
  color: #666;
  font-size: 1.2rem;
  &::before{
    content: "";
    right: 35%;
    padding: 0.4rem;
    border: inherit;
    border-right: 0;
    border-bottom: 0;
    background-color: inherit;
    transform: rotate(45deg) translate(-50%, -1.1rem);
    position: absolute;
  }
}
</style>