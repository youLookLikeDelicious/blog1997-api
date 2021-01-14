<template>
  <base-component :requestApi="requestApi" :showCreate="true">
    <!-- 搜索 begin -->
    <template v-slot:search>
      <v-input v-model="filter.name" placeholder="名称" theme="box" />
      <search-btn @search="search" />
    </template>
    <!-- 搜索 end -->
    <template
      v-slot:header="{ showCreateNewModel, toggleCreateNewModel, create }"
    >
      <create-tag
        v-if="showCreateNewModel"
        :toggleCreateNewModel="toggleCreateNewModel"
        :create="create"
      ></create-tag>
    </template>
    <template v-slot:default="{ data, update, deleteRecord }">
      <div v-if="data.records.length" class="sub-container">
        <ul class="tag-list">
          <li class="list-head">
            <span>编号</span>
            <span>名称</span>
            <span>封面</span>
            <span>描述</span>
            <span>操作</span>
          </li>
          <li
            v-for="(tag, index) in data.records"
            :key="tag.index"
            :class="[hiddenSubTag.includes(tag.parent_id) ? 'hid-tag' : 'show-tag']"
          >
            <template v-if="tag.id">
              <span :class="[{ 'indent-2': tag.parent_id }, 'flex tag-number']">
                <a
                  v-if="!tag.parent_id"
                  @click.stop.prevent
                  :class="[
                    'right-arrow',
                    'inline-block',
                    { 'down-arrow-to-right': hiddenSubTag.includes(tag.id) },
                  ]"
                  @click="toggleSubTag(tag.id)"
                  class="bolder"
                  href="/"
                  >▼</a
                >
                <span>&nbsp;&nbsp; {{ index + 1 }} </span></span
              >
              <span>
                {{ tag.name }}
              </span>
              <span>
                <img :src="tag.cover" alt="封面" />
              </span>
              <span>{{ tag.description }}</span>
              <span>
                <p>
                  <a
                    href="/"
                    class="icofont-edit link-btn-primary mr-min"
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
              </span>
            </template>
            <template v-else>
              <span class="show-more-sub-tag"
                ><a
                  @click.stop.prevent
                  @click="getSubTag(tag.p + 1, tag.parent_id, index)"
                  href="/"
                  >显示更多子标签 >></a
                ></span
              >
            </template>
          </li>
        </ul>
      </div>
      <create-tag
        v-if="$store.state.globalState.showDialog"
        :originModel="data.records[selectedIndex]"
        :create="update"
      ></create-tag>
    </template>
  </base-component>
</template>

<script>
import createTag from '~/components/article/tag/create-tag'

import dialogMixin from '~/mixin/dialog-init-data'
import search from '~/mixin/search'

export default {
  name: 'ArticleTag',
  mixins: [dialogMixin, search],
  data() {
    return {
      selectedIndex: '',
      hiddenSubTag: [],
      filter: {
        name: '',
      },
      requestApi: '/admin/tag',
      baseApi: '/admin/tag',
    }
  },
  components: {
    createTag,
  },
  methods: {
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
     * 伸展|收缩子标签
     *
     * @param {int} parentId
     */
    toggleSubTag(parentId) {
      const index = this.hiddenSubTag.findIndex((item) => item === parentId)
      let arr = [...this.hiddenSubTag]
      if (index === -1) {
        arr.push(parentId)
      } else {
        arr.splice(index, 1)
      }

      this.hiddenSubTag = arr
    },
    /**
     * 获取更多的子标签
     *
     * @param {int} p 请求的页数
     * @param {int} parent_id 父id
     * @param {int} index 当前记录的索引
     * @return void
     */
    getSubTag(p, parent_id, index) {
      this.$axios
        .get(`/admin/tag?parent_id=${parent_id}&p=${p}`)
        .then((response) => response.data.data)
        .then((data) => {
          if (!data.records.length) {
            return
          }
          // 判断是否还有更多的记录
          if (data.pagination.currentPage < data.pagination.pages) {
            data.records.push({
              parent_id: data.records[0].parent_id,
              p: data.pagination.currentPage,
              hasMore: true,
            })
          }
          // 更新结果
          const arr = [...this.$children[0].requestResult.records]
          arr.splice(index, 1, ...data.records)
          this.$children[0].requestResult.records = arr
        })
    },
  },
}
</script>

<style lang="scss">
.tag-list {
  list-style: none;
  box-shadow: 0.2rem 0rem 0.7rem 0.5rem #e6e6e6;
  .list-head {
    justify-content: center;
    font-weight: bold;
    border-left: 0;
    border-right: 0;
  }
  li {
    padding: 0.7rem;
    display: grid;
    align-items: center;
    overflow: hidden;
    grid-template-columns: 1fr 2fr 2fr 2fr 1fr;
    border-bottom: 0.1rem solid #e6e6e6;
    border-left: 0.1rem solid #c6c6c6;
    border-right: 0.1rem solid #c6c6c6;
    span {
      &:first-child {
        justify-self: center;
      }
    }
  }
  .show-more-sub-tag {
    grid-column-start: 2;
    grid-column: span 4;
    color: $blue;
  }
  img {
    width: 4.5rem;
  }
  .hid-tag {
    animation: hid-tag 0.5s;
    animation-fill-mode: forwards;
  }
  .indent-2 {
    text-indent: 5em;
  }

  @keyframes hid-tag {
    from {
      height: 6rem;
      border-bottom: 0.1rem solid #e6e6e6;
      border-left: 0.1rem solid #c6c6c6;
      border-right: 0.1rem solid #c6c6c6;
    }
    to {
      height: 0;
      border: 0;
      padding-top: 0;
      padding-bottom: 0;
    }
  }

  .show-tag {
    animation: show-tag 0.5s;
    animation-fill-mode: forwards;
  }
  @keyframes show-tag {
    from {
      height: 0;
      border: 0;
      padding-top: 0;
      padding-bottom: 0;
    }
    to {
      height: 6rem;
      border-bottom: 0.1rem solid #e6e6e6;
      border-left: 0.1rem solid #c6c6c6;
      border-right: 0.1rem solid #c6c6c6;
    }
  }
  .right-arrow {
    width: 1.2rem;
    color: #666;
    transition: transform 0.5s;
    vertical-align: middle;
  }
  .down-arrow-to-right {
    transform: rotate(-90deg);
  }
  .tag-number {
    align-items: center;
    height: 2rem;
  }
}
</style>