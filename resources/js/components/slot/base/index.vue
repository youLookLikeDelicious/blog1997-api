<template>
  <!-- 后台页面基础 模板 props: requestApi
    -- 请求数据成功后，会向父组件发送一个updated-data自定义事件，参数是从后台请求成功的数据
    -- 全选按钮的ref="selectAllEl"
    -- 全选按钮的事件selectAll($event)
    -- 判断是否全选 hasSelectAll
    -- 单击checkbox事件 clickCheckBox($event, idnex)
  -->
  <div class="base-wrapper relative-position">
    <div v-if="showHeader" class="sub-container mb-3">
      <div class="tool-bar flex">
        <div v-if="$scopedSlots.search" class="search-tools flex">
          <slot
            name="search"
            :data="requestResult"
            :batchDelete="batchDelete"
            :selectedCheckboxNum="selectedCheckboxNum"
          ></slot>
        </div>
        <div class="relative-position create-new-model-container flex mt-min">
          <slot
            name="header"
            :create="create"
            :getList="getList"
            :data="requestResult"
            :batchDelete="batchDelete"
            :update="update"
            :showCreateNewModel="showCreateNewModel"
            :selectedCheckboxNum="selectedCheckboxNum"
            :toggleCreateNewModel="toggleCreateNewModel"
          ></slot>
        </div>
        <a
          v-if="showCreate"
          href="/"
          @click.stop.prevent
          @click="toggleCreateNewModel"
          class="btn-enable"
          name="create"
        >
          <i class="icofont-ui-edit"></i> 新 建</a
        >
      </div>
    </div>
    <div
      v-if="
        (requestResult.records && requestResult.records.length) ||
        forceRenderContent
      "
      class="sub-container"
    >
      <slot
        :data="requestResult"
        :selectAll="selectAll"
        :clickCheckBox="clickCheckBox"
        :hasSelectAll="hasSelectAll"
        :deleteRecord="deleteRecord"
        :batchDelete="batchDelete"
        :getList="getList"
        :create="create"
        :update="update"
        :edit="edit"
      />
    </div>
    <!-- 分页 begin -->
    <div class="sub-container mt-4" v-if="!hiddenPagination">
      <pagination
        :default-limit="limit"
        :page-info="requestResult.pagination"
        @changePage="changePage"
      />
    </div>
    <!-- 分页 end -->
  </div>
</template>

<script>
export default {
  name: 'BaseComponent',
  props: {
    requestApi: {
      type: String,
      default() {
        return ''
      },
    },
    // 是否显示毛笔
    showCreate: {
      type: Boolean,
      default() {
        return false
      },
    },
    limit: {
      type: Number,
      default() {
        return 20
      },
    },
    showHeader: {
      type: Boolean,
      default() {
        return true
      },
    },
    forceRenderContent: {
      type: Boolean,
      default() {
        return false
      },
    },
    hiddenPagination: {
      type: Boolean,
      default() {
        return false
      },
    }
  },
  data() {
    return {
      requestResult: {
        records: '',
        pagination: '',
      },
      selectedCheckboxNum: 0,
      maxCheckboxNum: 0,
      showCreateNewModel: false, // 控制新建模型 组件的显隐
      queryString: '', // reload的时候调用
      currentLimit: this.limit,
    }
  },
  computed: {
    hasSelectAll() {
      return this.selectedCheckboxNum
        ? this.maxCheckboxNum === this.selectedCheckboxNum
        : -1
    },
  },
  watch: {
    requestApi() {
      if (this.requestApi) {
        this.getList()
      }
    },
    'requestResult.records'() {
      this.$emit('updated-data', this.requestResult)
    },
  },
  created() {
    this.getList()
  },
  methods: {
    /**
     * 请求列表
     *
     * @param String condition 请求列表额外的条件
     */
    getList(condition = '') {
      if (condition) {
        condition = '?' + condition
      } else if (this.currentLimit !== 20) {
        condition = `?limit=${this.currentLimit}`
      }

      this.queryString = condition.slice(1)

      if (this.requestApi.indexOf('?') >= 0) {
        condition = '&' + condition.slice(1)
      }

      //移除字符串中的符号 '&'
      if (condition[condition.length - 1] === '&') {
        condition = condition.slice(0, -1)
      }

      this.$axios.get(`${this.requestApi}${condition}`).then((response) => {
        const data = response.data.data

        if (!data.records) {
          this.requestResult = data
          return
        }

        this.requestResult = { ...data }

        this.maxCheckboxNum = this.requestResult.records.length
        this.selectedCheckboxNum = 0
      })
    },
    /**
     * 创建新的记录
     * 
     * @param {json} model 新的记录模型 Object
     * @return {Promise}
     */
    create(model) {
      return this.$axios
        .post(this.requestApi, model)
        .then((response) => response.data.data)
        .then((model) => {
          this.createdCallback(model)
        })
    },
    /**
     * 新建模型的回调
     * 将数据追加到list 顶部
     *
     * @param {json|array} model
     */
    createdCallback(model) {
      // 批量上传的结果
      if (!(model instanceof Array)) {
        model = [model]
      }

      const records = this.requestResult.records
      for (let i = 0, len = model.length; i < len; i++) {
        if (model[i].parent_id) {
          let parentIndex = records.findIndex(item => item.id === parseInt(model[i].parent_id))
          if (parentIndex < 0) {
            parentIndex = 0
          } else {
            // 有父元素，放在父元素之后
            ++parentIndex
          }
          records.splice(parentIndex, 0, model[i])
        } else {
          records.unshift(model[i])
        }
      }
      model.forEach(element => {
        
      });
      
      this.requestResult.pagination.total += model.length
    },
    /**
     * 模型的更新操作
     * 
     * @param {FormData} model
     * @return {Promise}
     */
    update(model) {
      // 获取修改的id
      const id = parseInt(model.get('id'))

      const fidnHandler = (item) => item.id === id
      let index = this.requestResult.records.findIndex(fidnHandler)

      model.append('_method', 'PUT')

      return this.$axios
        .post(`${this.getBaseUrl()}/${id}`, model)
        .then((response) => {
          // 没有找到元素，追加之
          if (index === -1) {
            index = this.requestResult.records.length
          }
          this.requestResult.records.splice(index, 1, response.data.data)
          this.$emit('updated', index)
        })
    },
    /**
     * 翻页操作
     * @param {json} query
     */
    changePage(query) {
      if (this.currentLimit !== query.limit) {
        this.currentLimit = query.limit
      }

      this.getList(`p=${query.p}&limit=${query.limit}`)
    },
    /**
     * 编辑元素
     * @param int index
     */
    edit(index) {
      this.requestResult.records[index].editAble = !this.requestResult.records[
        index
      ].editAble
    },
    /**
     * 全选操作
     * @param $e event事件
     */
    selectAll($e) {
      // 获取全选按钮的状态
      const btnState = $e.target.checked

      // 获取所有的input[name="index"]
      const inputs = document.querySelectorAll('input[name=index]')
      // 将所有的Input全部选中
      inputs.forEach((el) => {
        if (btnState !== el.checked) {
          el.click()
        }
      })

      // 更改当前被选中元素的状态,取消全选状态置为
      this.selectedCheckboxNum = btnState ? this.maxCheckboxNum : 0
    },
    /**
     * 点击选中框
     * @param e event事件
     * @param index 当前记录的索引
     */
    clickCheckBox(e, index) {
      if (e.target.checked) {
        this.selectedCheckboxNum += 1
      } else {
        this.selectedCheckboxNum -= 1
      }
    },
    /**
     * 删除相关记录
     *
     * @param {int} index
     * @param {function|undefined}
     */
    deleteRecord(index, deleted) {
      if (!window.confirm('确定要删除吗?')) {
        return
      }

      const records = this.requestResult.records

      const id = records[index].id

      this.$axios
        .post(`${this.getBaseUrl()}/${id}`, { _method: 'DELETE' })
        .then((response) => {
          this.afterDelete([id])
          return records
        })
        .then((records) => {
          if ('function' === typeof deleted) {
            deleted(records[index])
          }
        })
    },
    /**
     * 批量删除
     * @param json params 额外的参数
     * @returns {void}
     */
    batchDelete(params = {}) {
      // 获取所有checkbox
      const [checkbox, records, deleteIds] = [
        document.querySelectorAll('input[name=index]'),
        this.requestResult.records,
        [],
      ]

      checkbox.forEach((item, index) => {
        if (item.checked) {
          deleteIds.push(records[index].id)
        }
      })

      if (!deleteIds.length) {
        return
      }

      this.$axios
        .post(`${this.getBaseUrl()}/batch-delete`, {
          ids: deleteIds,
          _method: 'DELETE',
          ...params,
        })
        .then((response) => {
          this.afterDelete(deleteIds)
        })
    },
    /**
     * 批量删除之后的回调
     *
     * @param {array} ids
     * @return void
     */
    afterDelete(ids) {
      // 批量删除成功，修改记录的状态
      const filteredRecords = this.requestResult.records.filter(
        (item) => !ids.includes(item.id)
      )

      // 如果一次性全部删除完，重新对记录进行请求 (刷新当前页)
      if (!filteredRecords.length && this.requestResult.pagination.pages > 1) {
        this.getList()
        return
      }

      this.requestResult.records = filteredRecords

      // 更改全选和部分选中相关的状态
      if (this.selectedCheckboxNum > 1) {
        this.selectedCheckboxNum -= ids.length
      }
      this.maxCheckboxNum -= ids.length
      // 修改当前页总记录的数量
      this.requestResult.pagination.total -= ids.length

      this.clearCheckboxState()
    },
    /**
     * 删除记录后清除input的状态
     *
     * @return {void}
     */
    clearCheckboxState() {
      const checkbox = document.querySelectorAll('input[name="index"]')
      checkbox.forEach((item) => {
        if (item.checked) {
          item.checked = false
        }
      })
      this.selectedCheckboxNum = 0
    },
    /**
     * 控制新建专题 组件的显隐
     */
    toggleCreateNewModel() {
      this.showCreateNewModel = !this.showCreateNewModel
    },
    /**
     * 重新载入
     */
    reload() {
      this.getList(this.queryString)
    },
    /**
     * 返回baseURL
     *
     * @return {string}
     */
    getBaseUrl() {
      const questionMarkPosition = this.requestApi.indexOf('?')
      return questionMarkPosition >= 0
        ? this.requestApi.slice(0, this.requestApi.indexOf('?'))
        : this.requestApi
    },
  },
}
</script>

<style lang="scss">
.base-wrapper {
  width: 100%;
  box-sizing: border-box;
  .data-list {
    tr {
      // &:nth-of-type(odd) {
      //   background-color: #f2f2f2;
      // }
      &:hover {
        background-color: #e6f6fd;
      }
      &:first-child {
        th {
          padding: 2rem 0;
        }

        background-color: #e9e9e9;
      }
    }
    td {
      min-height: 5.3rem;
      height: 5.3rem;
      box-sizing: border-box;
    }
  }
  table {
    @extend %data-list-table;
    margin-left: auto;
    margin-right: auto;
    margin: 3rem auto;
    color: #606266;
  }
  .create-btn {
    color: $blue;
    transition: all 0.2s;
    font-size: 5rem;
    &:hover {
      font-size: 5.5rem;
    }
  }
  .search-tools {
    width: 100%;
    align-items: center;
  }
}
.editable-wrapper {
  position: relative;
  width: 100%;
  white-space: nowrap;
  height: 3rem;
  display: flex;
  align-items: center;
  box-sizing: border-box;
  margin: 0 auto;
  justify-content: center;
  a {
    position: absolute;
  }
}
.create-new-model-container {
  flex: 1 1 21rem;
  align-items: center;
  min-height: 4rem;
}
.tool-bar {
  justify-content: space-between;
  align-items: center;
  text-align: right;
  flex-wrap: wrap;
  .btn-enable {
    word-break: keep-all;
    white-space: nowrap;
  }
}
</style>
