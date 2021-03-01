<template>
  <!-- 查看和处理 用户举报的内容 -->
  <base-component
    request-api="/admin/illegal-info"
    @updated-data="updatedData"
    :showHeader="false"
  >
    <template v-slot:default="{ data }">
      <div class="illegal-info-head sub-container">
        <span>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 1000 1000"
            class="read-icon-svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
          >
            <glyph
              glyph-name="readernaut"
              unicode="&#xed61;"
              horiz-adv-x="10"
            />
            <path
              d="M94.1 415.1c20.400000000000006-69.30000000000001 75.5-82.10000000000002 85.30000000000001-83.90000000000003 54.099999999999994-127.5 181.9-217.1 331-217.1 149.30000000000007 0 277.30000000000007 89.9 331.20000000000005 217.79999999999998 1.6000000000000227 0.4000000000000341 3.2999999999999545 0.8000000000000114 5.5 1.5v-171.29999999999998c-19.200000000000045-7.699999999999989-32.700000000000045-26.19999999999999-32.700000000000045-47.89999999999999 0-28.5 23.5-51.7 52.5-51.7s52.5 23.200000000000003 52.5 51.7c0 24.499999999999986-17.299999999999955 44.89999999999999-40.5 50.3v184.89999999999998c16.899999999999977 12.300000000000011 34.700000000000045 32.5 44.5 65.70000000000005 0 0 17.600000000000023 85-11.199999999999932 106.5 0 0-14.100000000000023 19.799999999999955-54.90000000000009 35.10000000000002-3.199999999999932 11.899999999999977-6.899999999999977 23.699999999999932-11.199999999999932 35.09999999999991-15.100000000000023 2.400000000000091-31.600000000000023 5.300000000000068-48.80000000000007 8.800000000000068 25.100000000000023-43.5 39.30000000000007-93.30000000000001 39.30000000000007-146.20000000000005 0-170.2-147.5-308.2-329.40000000000003-308.2-181.89999999999998 0-329.4 138-329.4 308.2 0 54.400000000000034 15.099999999999994 105.39999999999998 41.5 149.70000000000005-15-3.300000000000068-29.400000000000006-6.2000000000000455-43.10000000000002-8.600000000000023-4.899999999999977-12.200000000000045-8.899999999999977-24.700000000000045-12.399999999999977-37.5l-0.6000000000000227-0.20000000000004547c-43.19999999999999-15.399999999999977-57.89999999999999-36.19999999999993-57.89999999999999-36.19999999999993-28.799999999999997-21.400000000000034-11.200000000000003-106.5-11.200000000000003-106.5z m405.9 380c-126.19999999999999-152-437.5-165.70000000000005-437.5-165.70000000000005v116c281.8 1.3000000000000682 437.5 192.10000000000002 437.5 192.10000000000002s155.70000000000005-190.79999999999995 437.5-192.10000000000002v-116s-311.29999999999995 13.700000000000045-437.5 165.70000000000005z m297.70000000000005-287.1c0 36.299999999999955-6.900000000000091 71-19.40000000000009 103-96.09999999999991 21.700000000000045-212.39999999999998 63.299999999999955-278.29999999999995 142.70000000000005-63.19999999999999-76.10000000000002-172.8-117.5-266.4-140-13.100000000000023-32.700000000000045-20.400000000000034-68.40000000000009-20.400000000000034-105.70000000000005 0-159 130.90000000000003-287.9 292.3-287.9 161.29999999999995-2.842170943040401e-14 292.20000000000005 128.89999999999998 292.20000000000005 287.9z m-430.20000000000005-45.60000000000002c0-17.799999999999955-14.699999999999989-32.299999999999955-32.80000000000001-32.299999999999955-18.099999999999966 0-32.80000000000001 14.5-32.80000000000001 32.299999999999955s14.700000000000045 32.30000000000001 32.80000000000001 32.30000000000001c18.100000000000023 0 32.80000000000001-14.5 32.80000000000001-32.30000000000001z m77.80000000000001-81.69999999999999c0-27.5-22.600000000000023-49.69999999999999-50.5-49.69999999999999s-50.5 22.30000000000001-50.5 49.69999999999999c0 27.5 22.599999999999966 49.69999999999999 50.5 49.69999999999999 27.899999999999977 0.10000000000002274 50.5-22.19999999999999 50.5-49.69999999999999z"
            />
          </svg>
        </span>
        <span>
          <a
            href="/"
            :class="{ 'is-showing': isShowTotal }"
            @click.prevent
            @click="
              () => {
                isShowTotal = true
              }
            "
            >全部: {{ data.total }}条记录</a
          >
        </span>
        /
        <span>
          <a
            href="/"
            :class="{ 'is-showing': !isShowTotal }"
            @click.prevent
            @click="
              () => {
                data = false
              }
            "
            >未读{{ requestResult.notHaveReadCount }}</a
          >
        </span>
        <div class="illegal-page-prompt">
          <p>批准:认证举报内容属实,将目标象予以删除</p>
          <p>忽略:将消息标记为已读,不做其他处理</p>
          <p>
            是否已读:
            <span class="orange-color">黄色</span>表示
            <span class="orange-color">未读</span>,灰色表示已读
          </p>
        </div>
      </div>
      <!-------------------------------------------------- 列表部分 ----------------------------------------------->
      <div class="sub-container">
        <table class="illegal-info-table data-list">
          <tr>
            <td class="text-no-wrap">编号</td>
            <td class="text-no-wrap">类型</td>
            <td class="text-no-wrap">举报内容</td>
            <td>举报原因</td>
            <td class="text-no-wrap">处理结果</td>
            <td class="text-no-wrap">操作</td>
          </tr>
          <tr v-for="(item, index) in data.records" :key="item.id">
            <td class="text-no-wrap">{{ index | filterListNumber(data.pagination.currentPage) }}</td>
            <td class="text-no-wrap">
              <i
                :class="[
                  item.type === 'App\\Model\\Article'
                    ? 'icofont-memorial'
                    : 'icofont-speech-comments',
                ]"
              />
              {{ item.type | mailboxType }}
            </td>
            <!-- 如果举报的是文章，渲染一个指向文章的连接 -->
            <td
              v-if="item.type === 'App\\Model\\Article'"
              class="text-align-left"
            >
              <a target="_blank" :href="item.content.split(',')[0]">{{
                item.content.split(',')[0]
              }}</a>
            </td>
            <!-- 如果举报的不是文章，直接显示举报的内容 -->
            <!-- eslint-disable-next-line vue/no-v-html -->
            <td
              v-else
              class="text-align-left"
              v-html="item.notificationable.content"
            />
            <td
              class="text-align-left"
              v-html="item.content.split(',')[1]"
            ></td>
            <td>
              <span v-if="item.operate === 'undo'" class="orange-color">
                未读
              </span>
              <span
                v-else-if="item.operate === 'ignore'"
                style="color: #5584ca"
              >
                忽略
              </span>
              <span v-else style="color: #bcf0a0"> 批准 </span>
            </td>
            <td class="text-no-wrap">
              <a
                href="/"
                title="批准"
                :class="[
                  item.have_read === 'yes' ? 'disable' : 'ignore',
                  'link-btn-primary',
                  'icofont-court-hammer',
                ]"
                @click.prevent
                @click="approveIllegalInfo(index)"
                >同意
              </a>
              <a
                href="/"
                title="忽略"
                :class="[
                  item.have_read === 'yes' ? 'disable' : 'ignore',
                  'link-btn-primary',
                  'icofont-focus',
                ]"
                @click.prevent
                @click="ignore(index)"
                >忽略
              </a>
            </td>
          </tr>
        </table>
      </div>
    </template>
  </base-component>
</template>

<script>
export default {
  name: 'Report',
  data() {
    return {
      requestResult: {},
      isShowTotal: true, // 显示全部的记录数
    }
  },
  methods: {
    /**
     * 批准举报的信息
     *
     * @param {Int} index 记录所在的索引
     */
    approveIllegalInfo(index) {
      const record = this.requestResult.records[index]

      // 已经处理，不再做任何其他处理
      if (record.operate !== 'undo') {
        return
      }

      this.$axios.post('/admin/illegal-info/approve/' + record.id).then(() => {
        // 将未读状态修改为已读状态
        record.operate = 'approve'
        this.decrementNotHaveRead()
      })
    },
    /**
     * 忽略举报的信息
     * 并将其标记为已读
     *
     *@param index 被标记已读的记录的记录的索引
     */
    ignore(index) {
      const record = this.requestResult.records[index]
      if (record.operate !== 'undo') {
        return
      }
      this.$axios.post('/admin/illegal-info/ignore/' + record.id).then(() => {
        // 标记已读成功，修改是否已读的状态
        record.operate = 'ignore'
        this.decrementNotHaveRead()
      })
    },
    /**
     * 将未读的记录-1
     */
    decrementNotHaveRead() {
      --this.requestResult.notHaveReadCount
    },
    /**
     * 接收子组件更新来的数据
     *
     * @param {Object} data
     */
    updatedData(data) {
      this.requestResult = { ...data }
    },
  },
}
</script>

<style lang="scss">
.illegal-info-table {
  @extend %data-list-table;
  .have-read {
    fill: #999;
  }
  .not-have-read {
    fill: #ffad33;
  }
  .disable {
    cursor: not-allowed;
    svg {
      fill: #999;
    }
  }
  .approve {
    color: $red;
  }
  .ignore {
    color: $blue;
  }
}
.read-icon-svg {
  width: 3.5rem;
  height: 3.5rem;
}
.illegal-page-prompt {
  margin-top: 1.2rem;
  font-size: 1rem;
  color: #999;
  p {
    padding: 0.3rem 0;
  }
}
.illegal-info-head {
  .is-showing {
    color: #276ace;
    font-weight: bold;
  }
}
</style>
