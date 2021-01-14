<template>
  <div id="app">
    <router-view class="editor-layout"></router-view>
    <waiting />
  </div>
</template>

<script>
export default {
    name: 'EditorLayout'
}
</script>

<style lang="scss">
.editor-layout{
    padding: 0;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>