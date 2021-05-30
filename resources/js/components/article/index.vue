<template>
  <base-component @updated-data="updatedData" :requestApi="requestApi">
    <template v-slot:header="{ data }">
      <div class="flex article-list-header">
        <div class="article-tab mb-min">
          <a
            href="/"
            @click.prevent
            @click="type = ''"
            :class="[
              'icofont-archive',
              { 'link-btn-primary': type === '' },
              'mr-min',
            ]"
            >全部 ({{ data.count ? data.count.total : 0 }})</a
          >
          <a
            href="/"
            @click.prevent
            @click="type = 'draft'"
            :class="[
              'icofont-archive',
              { 'link-btn-primary': type === 'draft' },
              'mr-min',
            ]"
            >草稿箱 ({{ data.count ? data.count.draft : 0 }})</a
          >
          <a
            href="/"
            @click.prevent
            :class="[
              'icofont-archive',
              { 'link-btn-primary': isDeleteList },
              'mr-min',
            ]"
            @click="type = 'deleted'"
            >回收站 ({{ data.count ? data.count.deleted : 0 }})</a
          >
        </div>
        <div class="flex topic-wrapper">
          <span class="mr-min">专题:</span>
          <v-select
            :options="data.topics"
            placeholder="请选择专题"
            v-model="topicId"
          ></v-select>
          <div class="order-by">
            <a
              @click.prevent
              @click="orderBy = 'new'"
              :class="
                orderBy === 'new' || !orderBy ? 'link-btn-primary' : 'link-btn'
              "
              href="/"
              >最新排序</a
            >
            <span>|</span>
            <a
              @click.prevent
              @click="orderBy = 'hot'"
              :class="orderBy === 'hot' ? 'link-btn-primary' : 'link-btn'"
              href="/"
              >最热排序</a
            >
          </div>
        </div>
        <router-link
          to="/article/create"
          class="icofont-quill-pen create-btn"
          title="写文章"
        ></router-link>
      </div>
    </template>
    <template v-slot:default="{ data, deleteRecord }">
      <ul class="article-list">
        <li
          v-for="(article, index) in data.records"
          :key="article.id"
          class="mb-min"
        >
          <div class="mb-min">
            <span class="draft-tab" v-if="article.is_draft === 'yes'"
              >草稿</span
            >
            <a
              v-if="
                article.is_draft === 'yes' || article.is_draft === undefined
              "
              @click.prevent
              href="/"
              ><h2>{{ article.title }}</h2></a
            >
            <a v-else target="__blank" :href="articleHref(article.id)"
              ><h2>{{ article.title }}</h2></a
            >
          </div>
          <div class="flex article-list-item-info-wrapper">
            <div class="article-list-item-info">
              <span class="mr-min">{{ article.updated_at | dateFormat }}</span>
              <span class="icofont-eye mr-min"> {{ article.visited }} </span>
              <span class="icofont-google-talk mr-min">
                {{ article.commented }}
              </span>
              <span class="icofont-thumbs-up mr-min">
                {{ article.liked }}
              </span>
            </div>
            <div>
              <a
                v-if="!isDeleteList"
                @click.prevent="createWeChatMaterial(article.id)"
                class="icofont-wechat link-btn-primary mr-min"
                href="/"
              >
                微信素材</a
              >
              <router-link
                v-if="type !== 'deleted'"
                class="icofont-edit link-btn-primary mr-min"
                :to="'/article/create/' + article.id"
              >
                编辑</router-link
              >
              <a
                v-if="isDeleteList"
                class="icofont-refresh link-btn-primary"
                href="/"
                @click.stop.prevent
                @click="restoreArticle(article.id)"
              >
                恢复</a
              >
              <a
                class="icofont-delete link-btn-danger"
                href="/"
                @click.stop.prevent
                @click="deleteRecord(index, deleted)"
              >
                {{ isDeleteList ? '彻底删除' : '删除' }}</a
              >
            </div>
          </div>
        </li>
      </ul>
    </template>
  </base-component>
</template>

<script>
import { createWeChatMaterial } from '~/api/article'
export default {
  name: 'ArticleList',
  data() {
    return {
      inited: false,
      topicId: '',
      previousTopciId: '',
      orderBy: '', // 排序的方式
      type: '', // 类型
    }
  },
  computed: {
    requestApi() {
      let baseUrl = '/admin/article?'
      const queryArr = []

      if (this.orderBy) {
        queryArr.push('order-by=' + this.orderBy)
        queryArr.push(`topicId=${this.topicId}`)
      } else if (this.previousTopciId !== this.topicId) {
        queryArr.push(`topicId=${this.topicId}`)
      }

      if (this.type) {
        queryArr.push('type=' + this.type)
      }

      return baseUrl + queryArr.join('&')
    },
    isDeleteList() {
      return this.type === 'deleted'
    },
  },
  watch: {
    /**
     * 监听topicid
     * 设置面包屑信息
     */
    topicId() {
      const finder = (item) => item.id === this.topicId
      const topic = this.$children[0].requestResult.topics.find(finder)

      this.updateArticleBread(topic)
    },
  },
  methods: {
    /**
     * 数据获取后的操作
     * 获取当前选中的topicId
     */
    updatedData(data) {
      if (this.inited) {
        return
      }

      this.inited = true
      if (!data.topics || !data.topics.length) {
        return
      }

      // 获取当前的专题
      const topicId = this.$route.query.topicId

      this.previousTopciId = this.topicId = data.currentTopicId
    },
    /**
     * 更新面包屑的状态
     *
     * @param {json} topic
     */
    updateArticleBread(topic) {
      if (!topic) {
        return
      }
      this.$store.commit('articleBread/setTopic', {
        id: topic.id,
        name: topic.name,
      })
    },
    /**
     * 获取文章预览地址
     *
     * @param {int} id
     * @return {string}
     */
    articleHref(id) {
      return '/article/' + btoa(id)
    },
    /**
     * 将文章从回收站回复
     *
     * @param {int} id
     */
    restoreArticle(id) {
      this.$axios
        .post('/admin/article/restore/' + id)
        .then((response) => {
          const records = this.$children[0].requestResult.records
          const index = records.findIndex((record) => record.id === id)
          records.splice(index, 1)
        })
        .then(() => {
          this.setStatisticCount({ total: 1, deleted: -1 })
        })
    },
    /**
     * 删除文章的回调
     *
     * @param {json} record
     */
    deleted(record) {
      let data = {}

      if (this.isDeleteList) {
        data.deleted = -1
      } else {
        data =
          record.is_draft === 'yes'
            ? { draft: -1, total: -1 }
            : { total: -1, deleted: 1 }
      }

      this.setStatisticCount(data)
    },
    /**
     * 设置文章统计的数量
     *
     * @param {string} type
     */
    setStatisticCount(type) {
      for (const key in type) {
        this.$children[0].requestResult.count[key] += type[key]
      }
    },
    /**
     * 创建微信素材
     *
     * @param {int} id 文章id
     */
    createWeChatMaterial(id) {
      createWeChatMaterial(id)
    },
  },
}
</script>

<style lang="scss">
.article-tab {
  width: 100%;
  color: #666;
  text-align: left;
}
.article-list {
  list-style: none;
  h2 {
    font-weight: bold;
    display: inline;
  }
  li {
    padding: 1rem 0;
    border-bottom: 0.1rem dotted rgb(221, 221, 221);
  }
  .article-list-item-info-wrapper {
    justify-content: space-between;
  }
  .article-list-item-info {
    color: #999;
  }
  .draft-tab {
    padding: 0.3rem 0.5rem;
    border: 0.1rem solid #ddd;
    box-sizing: border-box;
    border-radius: 0.3rem;
    color: #999;
  }
}
.article-list-header {
  width: 100%;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  .topic-wrapper {
    height: 6rem;
    align-items: center;
  }
  .order-by {
    margin-left: 1.5rem;
  }
}
</style>