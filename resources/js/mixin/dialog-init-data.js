/**
 * 提取编辑对话框中的方法
 */
export default {
    data () {
      return {
        editedModelIndex: '' // 被编辑的分类的索引,
      }
    },
    computed: {
      editedModel () {
        return this.$children[0].requestResult.records[this.editedModelIndex] || {}
      }
    },
    methods: {
      /**
       * 敏感词汇分类更新成功后的操作
       * @param category Object
       */
      updatedModel (category) {
        this.$children[0].requestResult.records[this.editedModelIndex] = category
      },
      /**
       * 显示对话框
       * @param index // 被选中的索引
       */
      showEditDialog (index) {
        this.editedModelIndex = index
        this.$showDialog()
      },
      close () {
        this.editedModelIndex = ''
      }
    }
  }
  