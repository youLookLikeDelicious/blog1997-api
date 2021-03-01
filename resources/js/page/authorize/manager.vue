<template>
  <base-component
    :requestApi="requestApi"
    :showCreate="true"
    @updated-data="updatedData"
  >
    <!-- 搜索 begin -->
    <template v-slot:search="{data}">
      <div class="mr-min">
        <v-input v-model="filter.email" theme="box" placeholder="用户邮箱"></v-input>
      </div>
      <v-select :options="data.roles" v-model="filter.role_id"></v-select>
      <search-btn @search="search" />
    </template>
    <!-- 搜索 end -->
    <template
      v-slot:header="{ showCreateNewModel, toggleCreateNewModel, update }"
    >
      <create-manager
        v-if="showCreateNewModel"
        :toggleCreateNewModel="toggleCreateNewModel"
        @create="update"
      />
    </template>
    <template v-slot:default="{ data, update }">
      <div v-if="data.records.length" class="sub-container">
        <table class="data-list">
          <tr>
            <th>#</th>
            <th>名称</th>
            <th>邮箱</th>
            <th>角色</th>
            <th>状态</th>
            <th>注册时间</th>
            <th>操作</th>
          </tr>
          <tr v-for="(manager, index) in data.records" :key="index">
            <td>{{ index | filterListNumber(data.pagination.currentPage) }}</td>
            <td>{{ manager.name }}</td>
            <td>{{ manager.email }}</td>
            <td class="manager-role-wrapper">
              <span v-for="(role, index) in manager.roles" :key="index" class="manager-role-tag">{{ role.name }}</span>
            </td>
            <td>
              {{
                manager.deleted_at
                  ? "待删除"
                  : manager.email_verified_at
                  ? "正常"
                  : "待激活"
              }}
            </td>
            <td>{{ manager.created_at | dateFormat }}</td>
            <td>
              <a
                href="/"
                class="icofont-edit link-btn-primary"
                @click.stop.prevent
                @click="edit(index)"
                >编辑</a
              >
            </td>
          </tr>
        </table>
      </div>
      <create-manager
        v-if="$store.state.globalState.showDialog"
        @create="update"
        :originUser="selectedUser"
        :origin-model="selectedModel"
      />
    </template>
  </base-component>
</template>

<script>
import createManager from "~/components/manager/create";
import search from '~/mixin/search'
export default {
  name: "Manager",
  components: {
    createManager,
  },
  mixins: [search],
  data() {
    return {
      selectedIndex: "",
      records: [],
      filter: {
        email: '',
        role_id: 0
      },
      requestApi: '/admin/manager',
      baseApi: '/admin/manager'
    };
  },
  computed: {
    /**
     * 选中的模型
     */
    selectedModel() {
      if (this.selectedIndex === "" || !this.records.length) {
        return {};
      }

      const selectedUser = this.records[this.selectedIndex];
      const origin = {
        id: selectedUser.id,
        email: selectedUser.email,
        roles: selectedUser.roles.slice(0),
      };

      origin.roles = origin.roles.map((role) => role.id);
      return origin;
    },
    /**
     * 选中的管理员
     */
    selectedUser() {
      if (this.selectedIndex === "" || !this.records.length) {
        return {};
      }

      const selectedUser = this.records[this.selectedIndex];

      return {
        avatar: selectedUser.avatar,
        name: selectedUser.name
      }
    },
  },
  methods: {
    /**
     * 修改标签
     *
     * @param {int} index
     * @reutrn void
     */
    edit(index) {
      this.$store.commit("globalState/showDialog", true);
      this.selectedIndex = index;
    },
    updatedData(data) {
      this.records = data.records;
    },
  },
};
</script>

<style lang="scss">
.manager-role-tag{
  padding: .3rem .5rem;
  display: inline-block;
  margin: .3rem .5rem;
  background-color: $blue;
  color: #fff;
  border-radius: .6rem;
  font-size: 1.2rem;
}
.manager-role-wrapper{
    white-space: normal;
    max-width: 19rem;
    box-sizing: border-box;
}
</style>