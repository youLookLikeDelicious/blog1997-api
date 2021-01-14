<template>
  <!--  专题列表-->
  <base-component :requestApi="requestApi" :showCreate="true">
    <template v-slot:search>
      <div class="mr-min">
        <div class="flex">
          <v-input v-model="keyword" placeholder="专题名称" theme="box"></v-input>
          <a href="/" @click.prevent @click="search" class="btn-enable ml-min"><i class="icofont-search-2"></i> 查 找</a>
        </div>
      </div>
    </template>
    <template
      v-slot:header="{ create, toggleCreateNewModel, showCreateNewModel }"
    >
      <create-topic
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
            <th>标题</th>
            <th>详情</th>
            <th>操作</th>
          </tr>
          <tr v-for="(item, index) in data.records" :key="item.id">
            <template v-if="item.editAble">
              <td class="relative-position" colspan="4" width="100%" height="100%">
                <create-topic
                  :origin-model="item"
                  @toggleComponent="edit(index)"
                  @create="update"
                />
              </td>
            </template>
            <template v-else>
              <td class="relative-position">{{ (data.pagination.currentPage - 1) * 20 + index + 1 }}</td>
              <td>
                <div class="editable-wrapper">
                  <router-link :to="'/article/' + item.id">{{
                    item.name
                  }}</router-link>
                  <!-- 修改专题组件-->
                </div>
              </td>
              <td class="detail">
                <span class="icofont-memorial">{{
                  item.article_sum || 0
                }}</span>
              </td>
              <!-- 按钮部分-->
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
          </tr>
        </table>
      </div>
    </template>
  </base-component>
</template>

<script>
import createTopic from "~/components/article/topic/create-topic";

const API = '/admin/topic'
export default {
  name: "topics",
  components: {
    createTopic,
  },
  data() {
    return {
      keyword: '',
      requestApi: API
    };
  },
  methods: {
    /**
     * 获取专题列表
     */
    gettopics(p) {
      this.$store.dispatch("article/gettopics", { vueInstance: this, page: p });
    },
    /**
     * 搜索专题
     */
    search() {
      if (!this.keyword) {
        this.requestApi = API
      } else {
        this.requestApi = `${API}?name=${this.keyword}`
      }
    }
  },
};
</script>

<style lang="scss">
</style>
