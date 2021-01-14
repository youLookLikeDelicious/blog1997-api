<!--
绑定父组件的状态
对象的格式与pageInfo props相同
<pagination :pageInfo="pageInfo" @changePage="changePage"/>
父组件监听此组件的changePage事件,页数作为唯一的参数
-->
<script>

export default {
  name: 'Pagination',
  props: {
    pageInfo: {
      type: [Object, String],
      default () {
        return {
          baseUrl: undefined,
          currentPage: undefined,
          next: undefined,
          previous: undefined,
          total: 0, // 总的记录数
          first: undefined,
          last: undefined,
          pages: undefined // 总的页数
        }
      }
    },
    defaultLimit: {
      type: Number,
      default () {
        return 20
      }
    }
  },
  data () {
    return {
      jumpPage: '', // input的值，绑定跳转的页面
      currentPage: '',
      previousLimit: this.defaultLimit,
      limit: this.defaultLimit
    }
  },
  methods: {
    changePage (p) {
      p = p || 1
      if (this.pageInfo.currentPage === p && this.limit === this.previousLimit) {
        return
      }
      this.currentPage = p
      this.previousLimit = this.limit
      this.$emit('changePage', {p, limit: parseInt(this.limit)})
    }
  },
  render (createElement) {
    if (!this.pageInfo || !this.pageInfo.total) {
      return createElement('p', {
        domProps: {
          innerHTML: `
          <svg xmlns="http://www.w3.org/2000/svg" height="60" width="60" viewBox="0 0 1000 100" xmlns:xlink="http://www.w3.org/1999/xlink">
<glyph glyph-name="yacht" unicode="&#xee40;" horiz-adv-x="1000" />
<path d="M916.4 508.9l21.100000000000023-46.099999999999966h-230.70000000000005l-58.89999999999998-53.19999999999999h-330.9l-90-65.40000000000003-20.400000000000006-4.399999999999977 71.9 69.59999999999997h-71.9s0 29 108.4 25.200000000000045c51.5-4.600000000000023 91 102.5 42.5 96.39999999999998 0 0-171.1-0.6000000000000227-185.8 0.39999999999997726-28 1.8000000000000682-53.099999999999994 53.10000000000002-67.79999999999998 90.20000000000005-1-1-7.5-2-36.30000000000001 1.1999999999999318-7.299999999999997 3.6000000000000227-4.699999999999996 20.300000000000068-4.699999999999996 20.300000000000068-3.5 10.100000000000023 27.6 16.600000000000023 27.6 16.600000000000023h673.4s216.70000000000005-126 152.5-150.80000000000007z m-339.4-29.69999999999999h-45.799999999999955l-82.20000000000005-43.89999999999998h136.39999999999998l-8.399999999999977 43.89999999999998z m24.399999999999977 3.1999999999999886l8.100000000000023-47.299999999999955h17.5l55.700000000000045 47.299999999999955h-81.30000000000007z m153.30000000000007 19.600000000000023l-4.5 0.10000000000002274-30.600000000000023-27.600000000000023h47.299999999999955l-12.199999999999932 27.5z m57.69999999999993-0.5c-13.899999999999977-0.10000000000002274-29 0.10000000000002274-44.89999999999998 0.39999999999997726l11.5-26.69999999999999h45l-11.600000000000023 26.30000000000001z m48.39999999999998 0.6999999999999886c-10.699999999999932-0.30000000000001137-22.699999999999932-0.5-35.59999999999991-0.8000000000000114l11.699999999999932-27.099999999999966h36l-12.100000000000023 27.899999999999977z m12.300000000000068 0.6000000000000227l12.399999999999977-28.400000000000034h33.89999999999998l-14.5 31.600000000000023c-7.899999999999977-1.5-18.799999999999955-2.5-31.799999999999955-3.1999999999999886z"/>
</svg>暂无记录`,
          className: 'no-records'
        }
      })
    }

    const dds = []

    for (let i = this.pageInfo.first, len = this.pageInfo.last; i <= len; i++) {
      dds.push(createElement('dd', {
        on: {
          click: () => {
            this.changePage(i, this.currentPage)
          }
        },
        class: {
          cur_page: i === this.pageInfo.currentPage,
        },
        domProps: {
          innerText: i
        },
        key: i
      }))
    }

    // 设置尾页和首页
    if (this.pageInfo.last !== 1) {
      dds.push(createElement('dd', {
        on: {
          click: () => {
            this.changePage(this.pageInfo.pages)
          }
        },
        domProps: {
          innerText: '尾页'
        },
        class: {
          disable: this.pageInfo.currentPage === this.pageInfo.last,
          'right-border': true
        }
      }))

      dds.unshift(createElement('dd', {
        on: {
          click: () => {
            this.changePage(1)
          }
        },
        domProps: {
          innerText: '首页'
        },
        class: {
          disable: this.pageInfo.currentPage === this.pageInfo.first
        }
      }))
    }

    // 每页显示多少的option
    const options = []
    options[0] = createElement('option', {
      domProps: {
        value: 10,
        innerText: '10',
        selected: 10 === this.defaultLimit
      }
    })
    options[1] = createElement('option', {
      domProps: {
        value: 20,
        innerText: '20',
        selected: 'selected',
        selected: 20 === this.defaultLimit
      }
    })
    options[2] = createElement('option', {
      domProps: {
        value: 50,
        innerText: '50',
        selected: 50 === this.defaultLimit
      }
    })

    options[3] = createElement('option', {
      domProps: {
        value: 100,
        innerText: '100',
        selected: 100 === this.defaultLimit
      }
    })

    // 控制每页显示多少的select
    dds.unshift(createElement('dt', [
      createElement('select', {
        on: {
          change: (e) => {
            this.limit = e.target.value
            this.changePage(1)
          }
        }
      }, options)
    ]))

    // 显示总的记录数
    dds.unshift(createElement('dt', {
      domProps: {
        innerText: `共${this.pageInfo.total}条 / ${this.pageInfo.pages}页`
      }
    }))

    // 跳转页input
    dds.push(createElement('dd', {
      domProps: {
        className: 'jump-input-wrap'
      }
    },
    [
      createElement('v-input', {
        props: {
          width: '7rem',
          placeholder: '1,2,3...'
        },
        on: {
          'update:value': (value) => {
            this.jumpPage = value.match(/^[1-9]\d*/)[0]
          }
        }
      })
    ]))
    // 跳转按钮
    dds.push(createElement('dt', [
      createElement('a', {
        domProps: {
          innerText: '跳转',
          className: 'btn',
          href: '/'
        },
        on: {
          // input中的内容必须是数字
          // input的取值范围为1 ~ pageInfo.pages
          click: (e) => {
            if (e.preventDefault) {
              e.preventDefault()
            } else {
              e.returnValue = false
            }

            if (!isNaN(this.jumpPage) && this.jumpPage > this.pageInfo.pages) {
              this.jumpPage = this.pageInfo.pages
            }

            // 如果跳转的是当前页，不予执行
            if (!this.jumpPage) {
              return
            }
            this.changePage(this.jumpPage)
            this.jumpPage = ''
          }
        }
      })
    ]))

    return createElement('dl', {
      domProps: {
        className: 'pagination'
      }
    }, dds)
  }
}
</script>

<style lang="scss">
  $li-height: 3rem;
  .pagination {
    margin-left: auto;
    margin-right: auto;
    margin-top: 5rem;
    text-align: center;
    font-size: 1.4rem;
    dt{
      display: inline-block;
      margin-right: 1.3rem;
      color: #666;
    }
    dd {
      display: inline-block;
      // margin-right: 0.7rem;
      box-sizing: border-box;
      height: $li-height;
      line-height: $li-height;
      padding: 0 .5rem;
      border: 0.1rem solid #c6c6c6;
      border-right: 0;
      cursor: pointer;
      text-align: center;
      &:hover {
        color: #20a4e9;
        border-color: #20a4e9;
      }
    }

    .right-border{
      border-right: .1rem solid #c6c6c6;
    }
    .end_point {
      padding-left: 0.4rem;
      padding-right: 0.4rem
    }

    .cur_page {
      cursor: default;
      border-color: #20a0ff;
      background-color: #20a0ff;
      color: #fff;
      &:hover {
        color: #fff;
      }
    }

    .disable {
      color: #c6c6c6;
      &:hover{
        color: #c6c6c6;
        border-color: #c6c6c6;
        cursor: not-allowed;
      }
    }
    .jump-input-wrap {
      border: 0;
      vertical-align: bottom;
      div{
        height: 100%;
      }
    }
    select, select option{
      outline: none;
      width: 7rem;
    }
    select {
      border-radius: .3rem;
    }
    select option{
      border: 0;
    }
    .v-input-inline{
      width: 7rem;
    }
  }
  .no-records{
    text-align: center;
    color: #666;
    svg{
      fill: #666;;
    }
  }
</style>
