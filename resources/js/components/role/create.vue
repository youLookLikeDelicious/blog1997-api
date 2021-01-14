<template>
  <v-dialog
    width="74rem"
    height="43rem"
    title="创建角色"
    @close="close"
  >
    <table class="create-role">
      <tr>
        <td class="required-feild">名称</td>
        <td>
          <div class="with-fit-content">
            <v-input v-model="name" placeholder="请填写角色名称"></v-input>
          </div>
        </td>
      </tr>
      <tr>
        <td>备注</td>
        <td>
          <textarea name="" v-model="remark" placeholder="备注"></textarea>
        </td>
      </tr>
      <tr>
        <td>授权</td>
        <td>
          <auth-tree :tree="$store.state.auth.auth"></auth-tree>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <submit-btn :allow-submit="allowSubmit" @submit="submit" />
        </td>
      </tr>
    </table>
  </v-dialog>
</template>

<script>
import compareOriginModelMixin from "~/mixin/compare-origin-model";
import AuthTree from './tree'

export default {
  name: "CreateRole",
  props: {
    originModel: {
      type: Object,
      default() {
        return {
          name: "",
          remark: "",
          authorities: []
        };
      },
    },
  },
  mixins: [compareOriginModelMixin],
  components: {
    AuthTree
  },
  data() {
    return {
      name: this.originModel.name,
      id: this.originModel.id,
      remark: this.originModel.remark
    };
  },
  computed: {
    /**
     * 是否允许提交
     */
    allowSubmit() {
      // 修改状态，判断是否能够提交
      return (
        this.checkModelIsDirty() &&
        this.model.name
      );
    },
    model () {
      return {
        name: this.name,
        remark: this.remark,
        authorities: this.$store.state.auth.selectedList,
        id: this.originModel.id
      }
    }
  },
  created() {
    this.$store.dispatch('auth/getAuth')
  },
  methods: {
    /**
     * 提交表单
     *
     * @reutrn void
     */
    submit() {
      this.$emit('create', this.$json2FormData(this.model))
      this.$children[0].close();
    },
    /**
     * 关闭对话框事件
     */
    close () {
      this.$emit('toggle')
      this.$store.commit('auth/clearSelectList')
    }
  },
};
</script>

<style lang="scss">
.create-role {
  box-shadow: unset !important;
  tr {
    &:hover {
      background-color: inherit !important;
    }
    td {
      vertical-align: top;
      &:first-child {
        width: 12rem;
        text-align: right !important;
      }
      &:last-child {
        text-align: left !important;
      }
    }
  }
  textarea{
    width: 17rem;
    height: 7rem;
    border: 0.1rem solid #c6c6c6;
    padding: .5rem;
  }
}
</style>