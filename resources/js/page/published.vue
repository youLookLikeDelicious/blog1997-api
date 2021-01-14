<template>
  <div>
    <div class="published sub-container">
      <p>
        <i class="icofont-memorial"></i>
      </p>
      <p>文章发布成功，慢慢人生路，又累计了一些回忆。</p>
      <p>
        <a
          target="_blank"
          :href="articleUrl"
        >{{ publishedArticle.title }}</a>
      </p>
    </div>
    <div class="share-to sub-container">
      分享到:
      <a href="/" @click.stop.prevent class="icofont-weibo"></a>
      <a href="/" @click.stop.prevent class="icofont-wechat"></a>
    </div>

    <div class="sub-container text-align-center mt-4 color-blue">
      <router-link :to="'/article/' + publishedArticle.topicId">返回文章列表</router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: "published",
  computed: {
    publishedArticle() {
      return this.$store.getters['article/publishedArticle'];
    },
    articleUrl () {
      return process.env.MIX_SENTRY_DSN_PUBLIC + '/article/' + btoa(this.publishedArticle.id)
    }
  },
  beforeDestroy() {
    this.$store.commit("article/setPublishedArticle", {});
  },
};
</script>

<style lang="scss">
.published {
  text-align: center;
  color: #666;
  .icofont-memorial {
    color: $blue;
    font-size: 9rem;
  }
  a {
    color: $blue;
    font-size: 1.8rem;
    text-decoration: underline !important;
  }
  p {
    margin-bottom: 3rem;
  }
}
.share-to {
  text-align: center;
  a {
    font-size: 2.3rem;
  }
  .icofont-weibo {
    color: $red;
    margin-right: 2rem;
    margin-left: 0.7rem;
  }
  .icofont-wechat {
    color: $green;
  }
}
</style>