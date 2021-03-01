<template>
  <base-component :request-api="requestApi" :showCreate="true">
    <!-- 搜索部分 begin -->
    <template v-slot:search="{ data, batchDelete, selectedCheckboxNum }">
      <v-input theme="box" v-model="filter.word" placeholder="敏感词"></v-input>
      <div class="inline-block ml-min">
        <v-select v-model="filter.category_id" :options="data.categoryList">
        </v-select>
      </div>
      <search-btn @search="search" />
      <!-- 敏感词分类列表 begin -->
      <div class="flex sensitive-word-tool-bar">
        <a
          href="/"
          :class="[
            { 'btn-disable': !selectedCheckboxNum },
            'btn-danger',
            'ml-min',
          ]"
          @click.stop.prevent
          @click="batchDelete"
          ><i class="icofont-ui-delete"></i> 删除选中</a
        >
      </div>
    </template>
    <!-- 搜索部分 end -->
    <!-- 提交新的敏感词 begin -->
    <template
      v-slot:header="{ create, data, showCreateNewModel, toggleCreateNewModel }"
    >
      <createSensitiveWord
        :categoryList="data.categoryList"
        v-if="showCreateNewModel"
        @toggleComponent="toggleCreateNewModel"
        @create="create"
      />
    </template>
    <!-- 提交新的敏感词 end -->
    <template
      ref="slotRef"
      v-slot:default="{
        data,
        hasSelectAll,
        update,
        selectAll,
        getList,
        edit,
        clickCheckBox,
        deleteRecord,
      }"
    >
      <div class="sub-container mb-3">
        <ul class="sensitive-word-menu">
          <li class="pointer-cursor">
            <a
              href="/"
              class="icofont-upload-alt"
              @click.stop.prevent
              @click="$showDialog"
              >导 入</a
            >
          </li>
          <li>
            <a href="/" @click.stop.prevent class="btn-disable">导 出</a>
          </li>
        </ul>
      </div>
      <!-- 数据列表部分 -->
      <div v-if="data.records.length" class="sub-container">
        <table class="sensitive-word-list data-list">
          <tr>
            <th>
              <v-checkbox
                ref="xy-checkbox"
                :selected-state="hasSelectAll"
                :click-event="selectAll"
              />
            </th>
            <th style="width: 13rem">编号</th>
            <th style="width: 40rem">敏感词</th>
            <th style="with: 25rem">分类</th>
            <th>操作</th>
          </tr>
          <tr v-for="(sensitiveWord, index) in data.records" :key="sensitiveWord.id">
            <template v-if="!sensitiveWord.editAble">
              <td class="relative-position">
                <input
                  :key="index"
                  name="index"
                  type="checkbox"
                  @click="clickCheckBox($event, index)"
                />
              </td>
              <td>{{ index | filterListNumber(data.pagination.currentPage) }}</td>
              <td>{{ sensitiveWord.word }}</td>
              <td>
                {{
                  data.categoryList.find(
                    (item) => item.id === sensitiveWord.category_id
                  ).name
                }}
              </td>
              <td>
                <a
                  class="icofont-ui-edit link-btn-primary"
                  href="/"
                  @click.stop.prevent
                  @click="edit(index)"
                  >编 辑</a
                >
                <a
                  class="icofont-ui-delete link-btn-danger"
                  href="/"
                  @click.stop.prevent
                  @click="deleteRecord(index)"
                  >删 除</a
                >
              </td>
            </template>
            <td v-else colspan="5" class="relative-position">
              <createSensitiveWord
                :categoryList="data.categoryList"
                @toggleComponent="edit(index)"
                :origin-model="sensitiveWord"
                @create="update"
              />
            </td>
          </tr>
        </table>
      </div>
      <!-- 敏感词列表 end -->
      <!-- 编辑敏感词和导入对话框 begin -->
      <import-dialog
        v-if="$store.state.globalState.showDialog"
        @update="update"
        :getList="getList"
        :category-list="data.categoryList"
        @imported="imported"
      />
      <!-- 编辑敏感词和导入对话框 end -->
    </template>
  </base-component>
</template>

<script>
import importDialog from '~/components/sensitive-word/import'
import createSensitiveWord from '~/components/sensitive-word/create'
import search from '~/mixin/search'
const requestApi = '/admin/sensitive-word'

export default {
  name: 'SensitiveWordIndex',
  components: {
    importDialog,
    createSensitiveWord,
  },
  mixins: [search],
  data() {
    return {
      filter: {
        category_id: 0,
        word: '',
      },
      requestApi: requestApi,
      baseApi: requestApi,
    }
  },
  methods: {
    imported (categeoryId) {
      if (categeoryId === this.filter.category_id) {
        this.$children[0].reload()
      } else {
        this.filter.category_id = categeoryId
        this.search()
      }
    }
  },
}
</script>

<style lang="scss">
.sensitive-word-menu {
  list-style: none;
  margin-bottom: 2rem;
  li {
    display: inline-block;
    position: relative;
    border: 0.1rem solid #5c9dff;
    padding: 0.3rem 0.7rem;
    margin-right: 0.5rem;
    border-radius: 0.3rem;
    color: #5c9dff;
    input {
      opacity: 0;
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
    }
  }
}
.sensitive-word-list {
  @extend %data-list-table;
}
.sensitive-word-tool-bar {
  align-items: center;
}
</style>
