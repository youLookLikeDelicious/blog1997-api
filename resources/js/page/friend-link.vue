<template>
  <!-- 友情链接的列表信息 -->
  <base-component :requestApi="requestApi" :showCreate="true">
    <!-- 搜索 begin -->
    <template v-slot:search>
      <v-input v-model="filter.name" placeholder="名称" theme="box"></v-input>
      <search-btn @search="search"></search-btn>
    </template>
    <!-- 搜索 end -->
    <template
      v-slot:header="{ create, toggleCreateNewModel, showCreateNewModel }"
    >
      <create-friendLink
        v-if="showCreateNewModel"
        @toggleComponent="toggleCreateNewModel"
        @create="create"
      />
    </template>
    <template v-slot:default="{ data, edit, update, deleteRecord }">
      <div class="sub-container">
        <table class="data-list">
          <tr>
            <th>#</th>
            <th>名称</th>
            <th>操作</th>
          </tr>
          <tr v-for="(item, index) in data.records" :key="item.id">
            <template v-if="!item.editAble">
              <td class="relative-position">{{ index | filterListNumber(data.pagination.currentPage) }}</td>
              <td>
                <a :href="item.url" target="_blank">{{ item.name }}</a>
              </td>
              <td>
                <p>
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
                </p>
              </td>
            </template>
            <template v-else>
              <td class="relative-position" colspan="3">
                <create-friendLink
                  @create="update"
                  @toggleComponent="edit(index)"
                  :origin-model="item"
                />
              </td>
            </template>
          </tr>
        </table>
      </div>
    </template>
  </base-component>
</template>

<script>
import createFriendLink from "~/components/friend-link/create";
import search from '~/mixin/search'
export default {
  name: "FriendLinkList",
  components: {
    createFriendLink,
  },
  mixins: [search],
  props: {
    linkList: {
      type: Array,
      default() {
        return [];
      },
    },
  },
  data() {
    return {
      showCreateFriendLinkBtn: false,
      filter: {
        name: ''
      },
      requestApi: '/admin/friend-link',
      baseApi: '/admin/friend-link'
    };
  },
  methods: {
    /**
     * 控制新建组件的显隐
     */
    toggleCreateFriendLink() {
      this.showCreateFriendLinkBtn = !this.showCreateFriendLinkBtn;
    },
  },
};
</script>

<style lang="scss">
.create-friend-link-wrap {
  display: flex;
  align-items: center;
}
</style>
