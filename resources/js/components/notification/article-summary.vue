<template>
  <div class="article-box">
    <a class="article-title inline-block" :href="'/article/' + article.id">{{
      article.title
    }}</a>
    <user-info :user="article.user" />
    <article class="article-summary" v-html="article.summary">
    </article>
  </div>
</template>

<script>
import UserInfo from "./user-info";
export default {
  name: "ArticleSummary",
  props: {
    article: {
      type: Object,
      default() {
        return {};
      },
    },
  },
  data() {
    return {};
  },
  components: {
    UserInfo,
  },
};
</script>

<style lang="scss">
.article-summary {
  line-height: 2rem;
  margin-top: 0.7rem;
  padding: 1rem 2rem;
  border-radius: 0.3rem;
  background-color: #f5f7fa;
}
.article-title {
  font-size: 2.7rem;
  font-weight: bolder;
  background-image: linear-gradient(to bottom right, #276ace, #29bdd9);
  background-clip: text;
  color: transparent !important;
  margin: .7rem 0;
}
</style>