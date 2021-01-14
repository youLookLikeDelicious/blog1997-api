<template>
  <base-component
    :requestApi="requestApi"
    :showCreate="true"
    @updated="updatedCallback"
    :limit="100"
  >
    <template v-slot:search="{ data }">
      <div class="flex search-auth">
        <div class="mr-min">
          <v-input
            placeholder="权限名称"
            theme="box"
            v-model="filter.name"
          ></v-input>
        </div>
        <div class="mr-min">
          <v-select
            v-model="filter.parent_id"
            :options="data.topAuth || []"
            placeholder="请选择父级权限"
          ></v-select>
        </div>
        <search-btn @search="search"></search-btn>
      </div>
    </template>
    <template
      v-slot:header="{ showCreateNewModel, toggleCreateNewModel, create }"
    >
      <create-auth
        v-if="showCreateNewModel"
        @toggle="toggleCreateNewModel"
        @create="create"
      />
    </template>
    <template v-slot:default="{ data, update, deleteRecord }">
      <div v-if="data.records.length" class="sub-container">
        <table class="auth-table data-list">
          <tr>
            <th>#</th>
            <th>名称</th>
            <th>Route Name</th>
            <th>操作</th>
          </tr>
          <tr v-for="(auth, index) in data.records" :key="index">
            <td>{{ index + 1 }}</td>
            <td class="text-align-left auth-table-name">
              <div :style="{ 'text-indent': calculateIndent(auth.auth_path) }">
                {{ auth.name }}
              </div>
            </td>
            <td>{{ auth.route_name || '' }}</td>
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
      <create-auth
        v-if="$store.state.globalState.showDialog"
        @create="update"
        :originModel="data.records[selectedIndex]"
      />
    </template>
  </base-component>
</template>

<script>
import createAuth from '~/components/auth/create'
import search from '~/mixin/search'

export default {
  name: 'Auth',
  data() {
    return {
      selectedIndex: '',
      filter: {
        parent_id: '',
        name: '',
      },
      requestApi: '/admin/auth',
      baseApi: '/admin/auth'
    }
  },
  mixins: [search],
  components: {
    createAuth
  },
  methods: {
    /**
     * 计算字符缩进的个数
     */
    calculateIndent(path) {
      path = path + ''
      return (path.match(/_/g).length - 1) * 2 + 'em'
    },
    /**
     * 修改标签
     *
     * @param {int} index
     * @reutrn void
     */
    edit(index) {
      this.selectedIndex = index
      this.$store.commit('globalState/showDialog', true)
    },
    /**
     * 更新后的操作，刷新当前页面
     */
    updatedCallback() {
      this.$children[0].reload()
    },
  },
  beforeDestroy() {
    this.$store.commit('globalState/showDialog', false)
  },
}
</script>

<style lang="scss">
.auth-table-name {
  padding-left: 0.7rem;
}
.search-auth{
  align-items: center;
}
</style>