<template>
  <base-component :requestApi="requestApi">
    <template v-slot:header="{}">
      <div class="user-log">
        <div class="inline-block">
          开始日期 <input type="date" v-model="startDate" />
        </div>
        <div class="inline-block">
          结束日期 <input type="date" v-model="endDate" />
        </div>
        <div class="inline-block">
          <a @click.prevent @click="search" class="btn-enable" href="/"
            ><i class="icofont-search-2"></i> 搜索</a
          >
        </div>
      </div>
    </template>
    <template v-slot:default="{ data }">
      <table class="log-table data-list">
        <tr>
          <th>#</th>
          <th>操作</th>
          <th>状态</th>
          <th>耗时</th>
          <th>时间</th>
          <th>备注</th>
        </tr>
        <tr v-for="(log, index) in data.records" :key="index">
          <td>{{ index + 1 }}</td>
          <td>{{ log.operate }}</td>
          <td>{{ log.result }}</td>
          <td>{{ log.time_consuming }}</td>
          <td>{{ log.created_at | dateFormat }}</td>
          <td>{{ log.message }}</td>
        </tr>
      </table>
    </template>
  </base-component>
</template>

<script>
const url = '/admin/log/schedule'
export default {
  name: 'ScheduleLog',
  data() {
    return {
      startDate: '',
      endDate: '',
      requestApi: url,
    }
  },
  methods: {
    /**
     * Search resource by condition
     *
     * @return void
     */
    search() {
      let urlStack = []
      if (this.name) {
        urlStack.push('name=' + this.name)
      }
      if (this.startDate) {
        urlStack.push('startDate=' + this.startDate)
      }
      if (this.endDate) {
        urlStack.push('endDate=' + this.endDate)
      }

      this.requestApi = url + (urlStack.length ? '?' + urlStack.join('&') : '')
    },
  },
}
</script>

<style lang="scss">
.user-log {
  input {
    color: #666;
    box-shadow: none;
    padding: 0.3rem 0.6rem;
    outline: transparent;
    border-radius: 0.4rem;
    box-sizing: border-box;
    height: 2.8rem;
    border: 0.1rem solid #c6c6c6;
  }
  div {
    vertical-align: middle;
    margin-right: 1.2rem;
  }
}
</style>