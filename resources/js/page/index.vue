<template>
  <div>
    <div class="site-info">
      <div class="sub-container">
        <div class="dash-board">
          <h2>统计数据</h2>
          <div class="icofont-speech-comments font-size-15"> 总评论数: {{totalCommented}}</div>
          <div class="icofont-thumbs-up font-size-15"> 收获点赞: {{totalLiked}}</div>
        </div>
      </div>
    </div>
    <div class="chart-wrap">
      <div ref="articleCategory" class="sub-container">
        <h2>文章分类</h2>
        <div id="article-chart" class="chart"></div>
      </div>
      <div ref="users" v-if="isMaster" class="sub-container">
        <h2>用户</h2>
        <div id="user-chart" class="chart"></div>
      </div>
      <div ref="illegalInfo" v-if="isMaster" class="sub-container">
        <h2>未处理举报信息</h2>
        <div id="illegal-chart" class="chart"></div>
      </div>
    </div>
  </div>
</template>

<script>
import renderArticleCategoryChartMixin from '~/mixin/dashboard/render-article-category-chart'
import renderUserChartMixin from '~/mixin/dashboard/render-user-chart'
import renderIlligalInfoChartMixin from '~/mixin/dashboard/render-illegal-info-chart'

/**
 * Dashbord page
 * @example [none]
 */
export default {
  name: "Index",
  data () {
    return {
      totalLiked: 0,
      totalCommented: 0,
      isMaster: this.$role('Master')
    }
  },
  mixins: [renderArticleCategoryChartMixin, renderUserChartMixin, renderIlligalInfoChartMixin],
  methods: {
    setMessage (selector, message) {
      document.getElementById(selector).innerHTML = '<div class="message">'+ message +'</div>'
    }
  },
  /**
   * 组件挂载完成，载入统计数据
   */
  mounted() {
    this.$axios.get("/admin/dashboard").then((response) => {
      const data = response.data.data;

      this.renderCategoryChart(data.articleInfo);

      if (this.$refs.users){
        this.renderUserChart(data.userInfo);
      }

      if (this.$refs.illegalInfo) {
        this.renderIllegalInfoChart(data.illegalInfo);
      }

      this.totalLiked = data.totalLiked
      this.totalCommented = data.totalCommented
    });
  },
};
</script>

<style lang="scss">
.site-info {
  padding-bottom: 0.7rem;
  margin-bottom: 1.2rem;
  h3 {
    margin-bottom: 1rem;
    font-size: 1.6rem;
  }
  div {
    margin-top: 0.7rem;
  }
  .dash-board {
    display: inline-block;
    margin: 0 2.4rem 2rem 0;
    div {
      color: #676869;
      margin: 2rem 1rem;
      display: inline;
    }
    h2 {
      margin-bottom: 2rem;
    }
  }
}
.chart-wrap {
  width: 100%;
  display: grid;
  grid-template-columns: 48% 48%;
  grid-template-rows: 35rem 35rem;
  grid-column-gap: 4%;
  grid-row-gap: 2rem;
  justify-items: stretch;
  h2 {
    margin-bottom: 2rem;
  }
  .chart{
    padding-top: 1rem;
    box-sizing: border-box;
    max-width: 72rem;
    .message {
      margin-top: 2rem;
      color: #666;
    }
  }
}
</style>
