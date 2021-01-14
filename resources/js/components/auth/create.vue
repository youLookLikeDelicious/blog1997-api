<template>
  <v-dialog
    width="74rem"
    height="40rem"
    title="创建标签"
    @close="$emit('toggle')"
  >
    <table class="create-auth">
      <tr>
        <td class="required-feild">名称</td>
        <td>
          <div class="with-fit-content">
            <v-input v-model="model.name" placeholder="请填写权限名称"></v-input>
          </div>
        </td>
      </tr>
      <tr>
        <td class="required-feild" style="vertical-align: top;">父级权限</td>
        <td>
          <cascade-select
            v-model="model.parent_id"
            :options="authList"
            placeholder="请选择父级权限"
          ></cascade-select>
        </td>
      </tr>
      <tr>
        <td>api路由名称</td>
        <td>
          <div class="with-fit-content">
            <v-input v-model="model.route_name" placeholder="请填写对应的路由"></v-input>
          </div>
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
import compareOriginModelMixin from '~/mixin/compare-origin-model'

export default {
  name: 'CreateAuth',
  props: {
    originModel: {
      type: Object,
      default() {
        return {
          name: '',
          parent_id: '',
          route_name: '',
        }
      },
    },
  },
  data() {
    return {
      model: {
        ...this.originModel,
      },
      parentAuth: [],
    }
  },
  mixins: [compareOriginModelMixin],
  computed: {
    /**
     * 是否允许提交
     */
    allowSubmit() {
      return (
        this.checkModelIsDirty() &&
        this.model.name &&
        parseInt(this.model.parent_id) >= 0
      )
    },
    authList() {
      const list = this.$store.state.auth.auth
      list.unshift({ id: 0, name: '--顶级权限--', auth_path: '0_' })
      return list
    },
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
      this.$children[0].close()
    },
    /**
     * 图标的点击事件
     *
     * @param {HTMLEvent} e
     */
    selectIcon(e) {
      this.model.icon =
        this.model.icon === e.target.className ? '' : e.target.className
    },
  },
  beforeDestroy() {
    this.$store.commit('auth/clear')
  },
}
</script>

<style lang="scss">
.create-auth {
  box-shadow: unset !important;
  tr {
    &:hover {
      background-color: inherit !important;
    }
    td {
      &:first-child {
        width: 12rem;
        text-align: right !important;
      }
      &:last-child {
        text-align: left !important;
      }
    }
    .v-input-inline {
      width: 21rem;
    }
  }
  .url-input {
    padding-left: 0.7rem;
    margin-top: 0.2rem;
  }
}
</style>