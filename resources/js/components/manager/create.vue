<template>
  <v-dialog
    width="74rem"
    height="27rem"
    title="添加管理员"
    @close="toggleCreateNewModel"
  >
    <div class="manager-user-info" v-if="user">
      <avatar :user="user" />
    </div>
    <table class="create-manager">
      <tr>
        <td class="required-feild">邮箱</td>
        <td>
          <v-input v-model="model.email" placeholder="请填写管理员邮箱"></v-input>
        </td>
      </tr>
      <tr>
        <td>角色</td>
        <td>
          <label v-for="(role, index) in roles" :key="index">
            <input type="checkbox" name="role" v-model="model.roles" :value="role.id" /> {{ role.name }}
          </label>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <submit-btn :allow-submit="allowSubmit" @submit="submit"></submit-btn>
        </td>
      </tr>
    </table>
  </v-dialog>
</template>

<script>
import compareOriginModelMixin from "~/mixin/compare-origin-model";

export default {
  name: "CreateManager",
  props: {
    originModel: {
      type: Object,
      default() {
        return {
          email: "",
          roles: [],
        };
      },
    },
    originUser: {
      type: [Object, String],
      default () {
        return ''
      }
    },
    create: {
      default() {
        return () => {};
      },
    },
    toggleCreateNewModel: {
      default() {
        return () => {};
      },
    },
  },
  mixins: [compareOriginModelMixin],
  data() {
    return {
      model: {
        email: this.originModel.email,
        roles: this.originModel.roles.slice(0),
        id: this.originModel.id
      },
      roles: [],
      user: this.originUser,
      timer: ''
    };
  },
  computed: {
    /**
     * 是否允许提交
     */
    allowSubmit() {
      return (
        this.checkModelIsDirty() && this.model.email && (this.user || this.originModel.id)
      );
    },
  },
  watch: {
    'model.email' () {
      if (!this.model.email) {
        return
      }
      if (this.timer) {
        clearTimeout(this.timer)
      }
      this.timer = setTimeout(() => {
        this.$axios.get('/admin/manager/user/' + this.model.email)
        .then(response => {
          const user = response.data.data
          this.user = user
          this.model.roles = user ? user.roles.map(role => role.id): ''
          this.model.id = user ? user.id : this.originUser
        })
        this.timer = ''
      }, 700)
    }
  },
  created() {
    this.getRoles();
  },
  methods: {
    /**
     * 提交表单
     *
     * @reutrn void
     */
    submit() {
      if (! this.allowSubmit) {
        return
      }
      const model = {...this.model}
      if (this.originModel.id) {
        model.id = this.originModel.id
      }

      this.$emit('create', this.$json2FormData(this.model))
      this.$children[0].close();
    },
    /**
     * 获取所有的角色
     */
    getRoles() {
      this.$axios
        .get("/admin/manager/create")
        .then((response) => (this.roles = response.data.data));
    },
  },
};
</script>

<style lang="scss">
.create-manager {
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
  textarea {
    width: 17rem;
    height: 7rem;
    border: 0.1rem solid #c6c6c6;
    padding: 0.5rem;
  }
}
.manager-user-info{
  margin-top: 2rem;
  margin-left: 1rem;
}
</style>