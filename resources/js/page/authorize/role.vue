<template>
  <base-component :requestApi="requestApi" :showCreate="true">
    <template v-slot:search>
      <div>
        <v-input v-model="filter.name" theme="box" placeholder="角色名称"></v-input>
      </div>
      <search-btn @search="search" />
    </template>
    <template
      v-slot:header="{ showCreateNewModel, toggleCreateNewModel, create }"
    >
      <create-role
        v-if="showCreateNewModel"
        @toggle="toggleCreateNewModel"
        @create="create"
      />
    </template>
    <template v-slot:default="{ data, update, deleteRecord }">
      <div v-if="data.records.length" class="sub-container">
        <table class="data-list">
          <tr>
            <th>#</th>
            <th>名称</th>
            <th>备注</th>
            <th>操作</th>
          </tr>
          <tr v-for="(role, index) in data.records" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ role.name }}</td>
            <td>{{ role.remark }}</td>
            <td>
              <a
                href="/"
                class="icofont-edit link-btn-primary"
                @click.stop.prevent
                @click="edit(index)"
                >编辑</a
              >
              <a
                href="/"
                class="icofont-delete link-btn-danger"
                @click.stop.prevent
                @click="deleteRecord(index)"
                >删除</a
              >
            </td>
          </tr>
        </table>
      </div>
      <create-role
        v-if="$store.state.globalState.showDialog"
        @create="update"
        :originModel="reflectRoleModel(data.records[selectedIndex])"
      />
    </template>
  </base-component>
</template>

<script>
import createRole from "~/components/role/create";
import search from '~/mixin/search'
export default {
  name: "Role",
  components: {
    createRole,
  },
  mixins: [search],
  data() {
    return {
      filter: {
        name: ''
      },
      requestApi: '/admin/role',
      baseApi: '/admin/role'
    }
  },
  methods: {
    /**
     * 修改标签
     *
     * @param {int} index
     * @reutrn void
     */
    edit(index) {
      this.selectedIndex = index;
      const authorities = this.$children[0].requestResult.records[index].authorities.map(item => item.id)
      this.$store.commit('auth/setSelectedList', authorities)
      this.$store.commit("globalState/showDialog", true);
    },
    /**
     * 映射role模型
     * @param {json} role
     * @return {json}
     */
    reflectRoleModel (role) {
      const reflected = { ...role }
      const mapper = (item) => item.id
      reflected.authorities = reflected.authorities.map(mapper)

      return reflected
    },
  },
  beforeDestroy () {
    this.$store.commit('auth/clear')
  }
};
</script>

<style lang="scss">
</style>