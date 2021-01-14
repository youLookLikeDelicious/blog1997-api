<template>
  <div class="card">
    <article-summary
      v-if="notificationAble.commentable.title"
      :article="notificationAble.commentable"
    />
    <comment v-else :comment="notificationAble.commentable" />
    <!-- comment list -->
    <comment :comment="comments"></comment>
  </div>
</template>

<script>
import ArticleSummary from "./article-summary";
import Comment from "./comment";
export default {
  name: "CommentSubject",
  props: {
    notificationAble: {
      type: Object,
      default() {
        return {
        };
      },
    },
  },
  data() {
    return {};
  },
  components: {
    Comment,
    ArticleSummary,
  },
  computed: {
    comments() {
      const arr = [
        ...this.notificationAble.commentable.comments.records,
      ];

      return arr;
    },
  },
};
</script>

<style lang="scss">
.comment-box {
  .comment{
      padding-bottom: .5rem;
      border-bottom: 0.1rem solid #e6e6e6;
      margin-bottom: 1rem;
  }
}
.article-box,
.comment-box {
  border-radius: 0.5rem;
  padding: 1rem 2rem;
  box-sizing: border-box;
  margin-top: 1.2rem;
  margin-bottom: 1.2rem;
}
</style>