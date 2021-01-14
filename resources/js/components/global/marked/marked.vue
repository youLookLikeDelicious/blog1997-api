<template>
  <div
    class="marked-editor flex"
    @dragover.prevent
    @drop.prevent
    @drop="dropImage"
  >
    <textarea ref="textarea" v-model="content" name="" spellcheck="false" @scroll="syncScroll"></textarea>
    <div ref="preview" class="marked-content" v-html="markedContent"></div>
  </div>
</template>

<script>
import marked from '~/plugins/vendor/marked'
export default {
  name: 'Marked',
  data() {
    return {
      content: '',
      markedContent: '',
    }
  },
  watch: {
    content() {
      this.markedContent = marked(this.content)
      if (this.content !== this.$attrs.value) {
        this.syncContent()
      }
    },
    '$attrs.value': {
      handler() {
        if (this.content !== this.$attrs.value) {
          this.getContentFromAttrs()
        }
      },
      immediate: true,
    },
  },
  methods: {
    /**
     * 拖拽图片事件
     *
     * @param {DragEvent} event
     * @return {void}
     */
    dropImage(event) {
      const [files, images] = [event.dataTransfer.files, []]

      for (let i = 0, len = files.length; i < len; i++) {
        if (/^image\/[a-zA-Z]*/.test(files[i].type)) {
          images.push(files[i])
        }
      }

      if (!images.length) {
        return
      }

      this.uploadImage(images)
    },
    /**
     * 上传图片
     *
     * @param {FileList} images
     * @return {void}
     */
    uploadImage(images) {
      let content = '\n'
      const formData = new FormData()

      images.forEach((image) => {
        formData.append('upfile[]', image)
      })

      this.$axios
        .post('/admin/upload/image/article?with-size=1', formData)
        .then((response) => response.data.data)
        .then((data) => this.appendImageContent(data))
    },
    /**
     * 向富文本中追加图片元素
     *
     * @param {array} images
     */
    appendImageContent(images) {
      console.log(images)
      let content = '\n'

      images.forEach((image) => {
        content += `![](${image})\n`
      })

      console.log(content)

      this.content += content
    },
    /**
     * 同步textarea的值
     */
    syncContent() {
      this.$emit('input', this.content)
    },
    /**
     * 设置 content的值
     */
    getContentFromAttrs() {
      this.content = this.$attrs.value
    },
    /**
     * 同步markdown预览界面的滚动条
     * 
     * @param {Event} event
     */
    syncScroll () {
      const scrollTop = this.$refs.textarea.scrollTop * this.$refs.preview.scrollHeight / this.$refs.textarea.scrollHeight
      this.$refs.preview.scroll({
        top: scrollTop,
        behavior: 'smooth'
      })
    }
  },
}
</script>

<style lang="scss">
.marked-editor {
  width: 100%;
  height: 100%;
  textarea {
    flex: 1;
    outline: none;
    border: 0;
    border-right: 0.1rem solid #999;
  }
  .marked-content {
    flex: 1;
  }
  textarea {
    padding: 1rem 2rem !important;
    font-size: 1.6rem;
    @extend %scroll-bar;
  }
}
.marked-content {
  box-sizing: border-box;
  padding: 1rem 2rem;
  font-size: 1.6rem;
  overflow: auto;
  @extend %scroll-bar;
  img {
    max-width: 100%;
  }
  pre{
    color: #333;
    background: #f8f8f8;
    padding: .5rem .7rem;
  }
}
</style>