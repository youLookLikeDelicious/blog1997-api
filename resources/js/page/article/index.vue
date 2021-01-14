<template>
  <div class="">
    <!-- 面包屑 -->
    <div v-if="routeName !== 'article.create'" class="bread sub-container">
      <router-link :to="listBread.to">{{ listBread.title }} /</router-link>
      <span v-if="topicBread.id">
        <router-link
          @click.stop.prevent
          :to="'/article?topicId=' + topicBread.id"
          title="专题"
          >{{ topicBread.name }}</router-link
        >
        /</span
      >
      <a
        @click.stop.prevent
        v-if="articleBread.title"
        :href="articleBread.id"
        title="当前文章"
        >{{ articleBread.title }}</a
      >
    </div>
    <transition name="next">
      <router-view></router-view>
    </transition>
  </div>
</template>

<script>
export default {
  name: "ArticleWrapper",
  computed: {
    routeName () {
      return this.$route.name
    },
    /**
     * 面包屑的专题信息
     *
     * @return array
     */
    topicBread() {
      if (this.routeName === "article.list") {
        return this.$store.state.articleBread.topic;
      }
      return "";
    },
    /**
     * 面包屑的文章信息
     *
     * @return array
     */
    articleBread() {
      if (this.routeName !== "article.create") {
        return "";
      }
      return this.$store.state.articleBread.article;
    },
    /**
     * 列表的面包屑信息
     */
    listBread() {
      switch (this.routeName) {
        case "article.topic":
          return {
            to: "/article/topic",
            title: "专题列表",
          };
        case "article.tag":
          return {
            to: "/article/tag",
            title: "标签列表",
          };
        default:
          return {
            to: "/article",
            title: "文章列表",
          };
      }
    },
  },
};
</script>

<style lang="scss">
.bread {
  margin-bottom: 1.2rem;
  color: #666;
}
</style>