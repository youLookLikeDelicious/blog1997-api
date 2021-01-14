<template>
  <!-- props categoryList -->
  <v-dialog title="导入敏感词汇" width="45rem" height="23rem" @close="close">
    <table class="import-sensitive-word">
      <tr>
        <td>分类</td>
        <td>
          <v-select :options="categoryList" v-model="importData.category_id" palceholder="屏蔽方式"></v-select>
        </td>
      </tr>
      <tr>
        <td>文件</td>
        <td>
          <input type="file" accept="text/plain" @change="changeFile($event)">
        </td>
      </tr>
      <tr>
        <td />
        <td><submit-btn @submit="submit" :allow-submit="allowSubmit"></submit-btn></td>
      </tr>
    </table>
  </v-dialog>
</template>

<script>
export default {
  name: 'ImportDialog',
  props: {
    categoryList: {
      type: Array,
      default () {
        return []
      }
    },
  },
  data () {
    return {
      importData: {
        file: '',
        category_id: 0
      }
    }
  },
  computed: {
    allowSubmit () {
      return this.importData.file && 1 * this.importData.category_id
    }
  },
  methods: {
    /**
     * 提交表单
     */
    submit () {
      if (!this.allowSubmit) {
        return
      }
      // 将模型转为formData
      const formData = new FormData()
      formData.append('category_id', this.importData.category_id)
      formData.append('file', this.importData.file)

      this.$axios.post('/admin/sensitive-word/import', formData)
        .then((response) => {
          // 隐藏对话框
          this.$children[0].close()
          this.$emit('imported', this.importData.category_id)
        })
    },
    /**
     * 修改文件事件
     * @param e
     */
    changeFile (e) {
      // 获取input上传的文件
      if (e.target.files.length) {
        this.importData.file = e.target.files[0]
      } else {
        this.importData.file = ''
      }
    },
    /**
     * 关闭对话框事件
     */
    close () {
      this.importData = { category_id: 0, file: '' }
    }
  }
}
</script>

<style lang="scss">
.import-sensitive-word{
  @extend %dialog-table;
}
</style>
