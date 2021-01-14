<template>
  <div class="comment-box" v-if="comments.length">
    <dl
      v-for="(comment, index) in comments"
      :key="index"
      @mouseenter="currentCommentIndex = index"
      @mouseleave="currentCommentIndex = -1"
    >
      <dt>
        <user-info :user="comment.user" />
        <p class="reply-date inline-block">
          {{ comment.created_at | dateFormat }}
        </p>
        <transition-group name="reply" tag="span">
          <a
            class="icofont-speech-comments"
            key="reply"
            v-if="currentCommentIndex === index"
            @click.prevent
            :data-id="comment.id"
            @click="clickReplyBtn"
            href="/"
            >回复</a
          >
          <a
            class="icofont-delete"
            @click.prevent
            key="delete"
            v-if="currentCommentIndex === index && comment.user_id === $store.state.user.id"
            :data-id="comment.id"
            @click="deleteComment"
            href="/"
            >删除</a
          >
        </transition-group>
      </dt>
      <dd class="comment" v-html="comment.content"></dd>
    </dl>
    <!-- 评论部分 -->
    <transition name="editor">
      <div v-show="editorVisible" ref="editor" class="editor-wrapper">
        <umeditor
          @receiveUM="receiveUM"
          :toolbar="['emotion', 'formula', 'source']"
          init-message=""
          width="95%"
          height="15rem"
        />
        <div class="btn-wrapper">
          <a @click.prevent @click="hidEditor" href="/">取消</a>
          <a @click.prevent @click="submitReply" href="/">提交</a>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import UserInfo from "./user-info";
export default {
  name: "Comment",
  props: {
    comment: {
      type: [Object, Array],
      default() {
        return [];
      },
    },
  },
  data() {
    return {
      editorVisible: false,
      editorPreviousParent: "",
      editor: "",
      currentCommentIndex: -1,
      currentCommentId: "",
      newComment: [],
      deletedId: [],
    };
  },
  components: {
    UserInfo,
  },
  computed: {
    comments() {
      let comments =
        this.comment instanceof Array
          ? this.comment
          : this.comment.content
          ? [this.comment]
          : [];

      comments = comments.concat(this.newComment);

      const filterCallback = (item) => {
        return !this.deletedId.includes(item.id);
      };
      return comments.filter(filterCallback);
    },
  },
  methods: {
    /**
     * 接收editor
     *
     * @param {object} editor
     * @return {void}
     */
    receiveUM(editor) {
      this.editor = editor;
    },
    /**
     * 隐藏评论框
     */
    hidEditor() {
      this.editorVisible = false;
    },
    /**
     * 提交回复
     *
     * @param {HTMLEvent} event
     * @return {void}
     */
    submitReply(event) {
      // 获取评论的内容
      const content = this.editor.getContent().trim();
      if (!content) {
        return;
      }

      this.$axios
        .post("/comment", {
          able_type: "comment",
          able_id: this.currentCommentId,
          content,
        })
        .then((resposne) => {
          this.newComment.push({
            ...resposne.data.data,
            user: this.$store.state.user,
          });
          this.hidEditor();
          this.editor.setContent('')
        });
    },
    /**
     * 点击 回复按钮事件
     *
     * @param {HTMLEvent} event
     * @return {void}
     */
    clickReplyBtn(event) {
      this.currentCommentId = event.target.dataset.id;
      const container = event.target.parentElement.parentElement.parentElement;
      if (
        !this.editorPreviousParent ||
        container !== this.editorPreviousParent
      ) {
        this.editorPreviousParent = container;

        this.editorVisible = true;
        container.appendChild(this.$refs.editor);
        return;
      }

      this.editorVisible = !this.editorVisible;
    },
    clickInWindow(event) {
      if (!this.$checkElementAncestor(event.target, this.$el)) {
        this.hidEditor();
      }
    },
    /**
     * 删除评论
     *
     * @param {HTMLEvent} event
     * @return {void}
     */
    deleteComment(event) {
      const id = event.target.dataset.id;
      this.$axios
        .post("/comment/" + id, {
          _method: "delete",
        })
        .then(() => {
          this.deletedId.push(parseInt(id));
        });
    },
  },
  mounted() {
    window.addEventListener("click", this.clickInWindow);
  },
  beforeDestroy() {
    window.removeEventListener("click", this.clickInWindow);
  },
};
</script>

<style lang="scss">
.editor-enter-active,
.editor-leave-active {
  transition: all 0.3s linear;
  overflow: hidden;
  height: 22rem;
}
.editor-enter,
.editor-leave-to {
  height: 0;
  opacity: 0;
}
.comment-box {
  .reply-date {
    color: #777;
    font-size: 1.2rem;
    padding-left: 4rem;
    margin-bottom: 1.2rem;
    margin-right: 1.2rem;
  }
  .icofont-delete{
    margin-left: 1.2rem;
  }
}
.reply-enter-active,
.reply-leave-active {
  transition: all 0.3s linear;
}
.reply-enter,
.reply-leave-to {
  opacity: 0;
  margin-left: -2rem;
}
</style>