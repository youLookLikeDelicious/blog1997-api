<template>
  <a href="/" @click.prevent @click="$emit('search')" class="btn-enable ml-min">
    <i class="icofont-search-2"></i> 查询
  </a>
</template>

<script>
export default {
  name: 'Search'
}
</script>

<style>

</style>