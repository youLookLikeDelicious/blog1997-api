<template>
  <base-component :request-api="requestApi" :showCreate="true">
    <!-- 搜索 begin -->
    <template v-slot:search>
      <div class="mr-min">
        <v-input
          theme="box"
          v-model="filter.name"
          placeholder="分类名称"
        ></v-input>
      </div>
      <v-select
        :options="shieldLevel"
        v-model="filter.rank"
        placeholder="屏蔽级别"
      ></v-select>
      <search-btn @search="search" />
    </template>
    <!-- 搜索 end -->
    <!-- 新建分类 begin -->
    <template
      v-slot:header="{ create, toggleCreateNewModel, showCreateNewModel }"
    >
      <createCategory
        v-if="showCreateNewModel"
        @toggleComponent="toggleCreateNewModel"
        @create="create"
        :shield-level="shieldLevel.slice(1)"
      />
    </template>
    <!-- 新建分类 end -->
    <template v-slot:default="{ data, deleteRecord, update, edit }">
      <div>
        <!-- 分类列表开始 -->
        <div class="sub-container">
          <table class="sensitive-word-category data-list">
            <tr>
              <th>#</th>
              <th>名称</th>
              <th>屏蔽级别</th>
              <th>敏感词数量</th>
              <th>操作</th>
            </tr>
            <tr v-for="(item, index) in data.records" :key="index">
              <template v-if="!item.editAble">
                <td class="relative-position">
                  {{ index | filterListNumber(data.pagination.currentPage) }}
                </td>
                <td>{{ item.name }}</td>
                <td>{{ item.rank | sensitiveWordRank }}</td>
                <td>{{ item.count || 0 }}</td>
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
                <!-- 修改敏感词汇分类 begin -->
                <createCategory
                  :origin-model="data.records[index]"
                  @toggleComponent="edit(index)"
                  :shield-level="shieldLevel.slice(1)"
                  @create="update"
                />
                <!-- 修改敏感词汇分类 end -->
              </td>
            </tr>
          </table>
        </div>
      </div>
    </template>
  </base-component>
</template>

<script>
import createCategory from '~/components/sensitive-word/category/create'
import search from '~/mixin/search'

const shieldLevel = [
  {id: '', name: '--全部--'},
  { id: 1, name: '替换' },
  { id: 2, name: '审核' },
  { id: 3, name: '屏蔽' },
]
export default {
  name: 'SensitiveWordCategory',
  mixins: [search],
  components: {
    createCategory,
  },
  data() {
    return {
      showCreateCategory: false,
      filter: {
        name: '',
        rank: 0,
      },
      requestApi: '/admin/sensitive-word-category',
      baseApi: '/admin/sensitive-word-category',
      shieldLevel
    }
  },
  methods: {
    /**
     * 控制新建敏感词分类的显隐
     */
    addSensitiveWordGroup() {
      const tmpStatus = !this.showCreateCategory
      if (tmpStatus) {
        this.$animate(document.querySelector('#new-category'), {
          'margin-bottom': '1rem',
          duration: 300,
        })
      } else {
        this.$animate(document.querySelector('#new-category'), {
          'margin-bottom': '0px',
          duration: 200,
        })
      }
      this.showCreateCategory = tmpStatus
    },
    /**
     * 添加新的分类
     * @param Object category
     */
    addCategory(category) {
      this.list.unshift(category)
      this.pageInfo.total += 1
    },
  },
}
</script>

<style lang="scss">
.sensitive-word-category {
  @extend %data-list-table;
}
.new-sensitive-word-category {
  font-weight: normal;
}
</style>
