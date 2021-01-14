<template>
  <!-- 审核评论 -->
  <base-component :requestApi="requestApi" @updated-data="updatedData">
    <template v-slot:header="{data}">
      <div>
        <a
          @click.prevent
          href="/"
          :class="['link-btn', 'mr-min', { 'link-btn-primary': !haveRead }]"
          @click="haveRead = ''"
          >全部 ({{ data.counts ? data.counts.total : 0 }})</a
        >
        <a
          @click.prevent
          href="/"
          :class="[
            'link-btn',
            'mr-min',
            { 'link-btn-primary': haveRead === 'yes' },
          ]"
          @click="haveRead = 'yes'"
          >已读({{ data.counts ? data.counts.have_read : 0 }})</a
        >
        <a
          @click.prevent
          href="/"
          :class="[
            'link-btn',
            'mr-min',
            { 'link-btn-primary': haveRead === 'no' },
          ]"
          @click="haveRead = 'no'"
          >未读({{ data.counts ? data.counts.total - data.counts.have_read : 0}})</a
        >
      </div>
    </template>
    <template v-slot:default="{ data }">
      <div v-if="data.records.length" class="sub-container">
        <ul
          v-for="(
            { notificationable, user, content, created_at, type }, index
          ) in data.records"
          :key="index"
          class="notification-list"
        >
          <li class="notification-list-li">
            <div class="notification-header">
              <avatar :user="user" />
              <div class="inline-block">
                <p class="mr-min">
                  <span> {{ content }}</span>
                </p>
                <p>
                  <span>{{ created_at | dateFormat }}</span>
                </p>
              </div>
            </div>
            <div class="notification-subject">
              <!-- 被评论通知 -->
              <div v-if="type === 'App\\Model\\Comment'">
                <comment
                  :comment="{
                    id: notificationable.id,
                    content: notificationable.content,
                    user: user,
                    created_at: created_at,
                  }"
                />
                <comment-subject :notification-able="notificationable" />
                <a
                  v-if="
                    notificationable.commentable.comments.pagination
                      .currentPage <
                    notificationable.commentable.comments.pagination.last
                  "
                  @click.prevent
                  @click="getMoreReply(index)"
                  href="/"
                  >更多评论>></a
                >
              </div>
              <!-- 被点赞通知 -->
              <div v-else>
                <div v-if="notificationable.able_type === 'App\\Model\\Article'" class="mt-min">
                  <a :href="articleLink(notificationable.thumbable.id)" class="article-title" target="__blank">{{notificationable.thumbable.title}}</a>
                </div>
                <div v-else class="mt-min">
                  <div v-html="notificationable.thumbable.content"></div>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </template>
  </base-component>
</template>

<script>
import CommentSubject from "~/components/notification/comment-subject";
import comment from "~/components/notification/comment";
const requestApi = "/admin/notification";
export default {
  name: "Notification",
  data() {
    return {
      haveRead: "", // 标注消息的类型
    };
  },
  computed: {
    requestApi() {
      return this.haveRead ? `${requestApi}?have_read=${this.haveRead}` : requestApi;
    },
  },
  components: {
    CommentSubject,
    comment,
  },
  methods: {
    /**
     * 剪切文章的内容
     *
     * @param {string} content
     * @return {string}
     */
    pruneContent(content) {
      const moreIndex = content.search(/<!\-\-\s*more\s*\-\-\>/);
      if (moreIndex >= 0) {
        return content.slice(0, moreIndex);
      }

      let pureText = content.replaceAll(/<[^>]*>/g, "");
      return `<pre>${pureText.slice(0, 350)}...</pre>`;
    },
    /**
     * 获取更多的评论
     *
     * @param {int} index
     * @return {void}
     */
    getMoreReply(index) {
      const notification = this.$children[0].requestResult.records[index];
      this.$axios
        .get(
          `/admin/notification/commentable-comments/${notification.id}?p=${notification.notificationable.commentable.comments.pagination.next}`
        )
        .then((response) => {
          const comments = this.$children[0].requestResult.records[index]
            .notificationable.commentable.comments;

          comments.pagination = response.data.data.pagination;
          comments.records = comments.records.concat(
            response.data.data.records
          );
        });
    },
    updatedData (data) {
      this.haveRead = data.haveRead
    },
    /**
     * 生成article link
     * 
     * @param {int} id article id
     */
    articleLink (id) {
      return '/article/' + btoa(id)
    }
  },
};
</script>

<style lang="scss">
.notification-list {
  list-style: none;
  &::before{
    content: '#';
    display: inline;
  }
  .notification-list-li {
    margin: 3rem 0;
    padding-bottom: 5rem;
    border-bottom: 0.1rem solid #c0d6ff;
  }
  .notification-header,
  .notification-header {
    display: flex;
    align-items: center;
    span {
      color: #999;
      font-size: 1.2rem;
    }
  }
  .reply {
    margin-top: 2rem;
    color: #666;
    text-align: right;
  }
  .comment {
    padding-left: 5rem;
  }
}
.editor-wrapper {
  margin: 0 auto;
  .edui-container {
    margin: 0 auto;
  }
  .edui-popup-emotion .edui-tab-content {
    padding: 0 15px !important;
  }
  .btn-wrapper {
    margin-top: 1rem;
    text-align: right;
    padding-right: 4rem;
    a {
      margin-left: 1rem;
    }
  }
}
.notification-subject {
  padding-left: 3rem;
}
</style>
