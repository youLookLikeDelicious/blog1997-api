<template>
  <!-- 审核评论 -->
  <base-component requestApi="/admin/comment">
    <template v-slot:header="{ selectedCheckboxNum }">
      <div>
        <a
          href="/"
          @click.stop.prevent
          @click="batchOperate('approve')"
          :class="['btn-enable', { 'btn-disable': !selectedCheckboxNum}]"
          ><i class="icofont-tick-mark"> </i> 批量同意</a
        >
        <a
          href="/"
          @click.stop.prevent
          @click="batchOperate('reject')"
          :class="['btn-danger', { 'btn-disable': !selectedCheckboxNum}]"
          ><i class="icofont-ui-delete"> </i> 批量回绝</a
        >
      </div>
    </template>
    <template
      v-slot:default="{
        data,
        selectAll,
        hasSelectAll,
        clickCheckBox,
      }"
    >
      <div v-if="data.records.length" class="sub-container">
        <form ref="form" action="/">
          <table class="comments-to-verify data-list">
            <tr>
              <th>
                <v-checkbox
                  ref="xy-checkbox"
                  :selected-state="hasSelectAll"
                  :click-event="selectAll"
                />
              </th>
              <th>#</th>
              <th>评论者</th>
              <th>内容</th>
              <th>评论时间</th>
              <th>操作</th>
            </tr>
            <tr v-for="(item, index) in data.records" :key="item.id">
              <td>
                <input
                  :key="index"
                  :value="item.id"
                  name="index"
                  type="checkbox"
                  @click="clickCheckBox($event, index)"
                />
              </td>
              <td>{{ index | filterListNumber(data.pagination.currentPage) }}</td>
              <td class="text-align-left">
                <avatar :user="item.user" />
              </td>
              <td class="text-align-left comment-content">
                <div v-html="item.content"></div>
              </td>
              <td>{{ item.created_at | dateFormat }}</td>
              <td>
                <p>
                  <a
                    href="/"
                    class="btn-enable inline-block"
                    @click.stop.prevent
                    @click="approve(item.id)"
                  >
                    <i class="icofont-edit"> </i> 批 准</a
                  >
                  <a
                    href="/"
                    class="btn-danger inline-block"
                    @click.stop.prevent
                    @click="reject(item.id)"
                    ><i class="icofont-delete"> </i> 回 绝</a
                  >
                </p>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </template>
  </base-component>
</template>

<script>
export default {
  name: "VefiryComments",
  data() {
    return {};
  },
  methods: {
    /**
     * 批量审核评论
     * 
     * @param {string} operate [approve|reject]
     */
    batchOperate(operate) {
      const form = new FormData(this.$refs.form)

      const mapper = (item) => parseInt(item)
      const ids = form.getAll('index').map(mapper)
      
      if (!ids.length) {
        return
      }

      /**
       * 批量操作提交的数据
       * @var {json}
       */
      const postData = {
        ids: ids
      }

      // reject api需要DELETE方法
      if (operate === 'reject') {
        postData._method = 'DELETE'
      }

      this.$axios
        .post(`/admin/comment/${operate}`, postData)
        .then(() => {
          this.$children[0].afterDelete(ids);
        });
    },
    /**
     * 评论通过
     *
     * @param {int} id
     * @return {void}
     */
    approve(id) {
      this.$axios
        .post("/admin/comment/approve", {
          ids: [id],
        })
        .then(() => {
          this.$children[0].afterDelete([id]);
        });
    },
    /**
     * 驳回评论
     *
     * @param {int} id
     * @return {void}
     */
    reject(id) {
      this.$axios
        .post("/admin/comment/reject", {
          _method: "delete",
          ids: [id],
        })
        .then(() => {
          this.$children[0].afterDelete([id]);
        });
    },
  },
};
</script>

<style lang="scss">
.comments-to-verify{
  .comment-content{
      width: 55rem;
      max-width: 55rem;
  }
}
</style>
