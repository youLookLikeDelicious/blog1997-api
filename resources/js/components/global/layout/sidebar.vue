<template>
  <div class="sidebar">
    <ul v-for="(menu, index) in menus" :key="index">
      <li
        class="header"
        @click.capture="toggleMenu"
        v-if="$can(menu.permission)"
      >
        <span
          ><i v-if="menu.icon" :class="menu.icon"></i><span class="sidebar-title">{{ menu.header }}</span></span
        >
        <span><i class="icofont-caret-down"></i></span>
      </li>
      <li v-if="menu.subMenu && $can(menu.permission)">
        <ul class="sub-menu">
          <li
            v-for="(subMenu, subIndex) in filterMenu(menu.subMenu)"
            :key="subIndex"
            :class="{ on: currentPath === subMenu.url }"
          >
            <i v-if="subMenu.icon" :class="subMenu.icon"></i>
            <router-link :to="subMenu.url">{{ subMenu.title }}</router-link>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'Sidebar',
  data() {
    return {
      menus: [
        {
          header: '工作台',
          icon: 'icofont-settings',
          permission: 'admin.dashboard',
          subMenu: [
            {
              url: '/',
              title: '仪表盘',
              icon: 'icofont-dashboard',
              permission: 'admin.dashboard',
            },
          ],
        },
        {
          header: '博客管理',
          icon: 'icofont-memorial',
          permission: [
            'article.index',
            'topic.index',
            'article.create',
            'tag.index',
          ].join(','),
          subMenu: [
            {
              url: '/article',
              title: '文章列表',
              icon: 'icofont-attachment',
              permission: 'article.index',
            },
            {
              title: '专题列表',
              url: '/article/topic',
              icon: 'icofont-archive',
              permission: 'topic.index',
            },
            {
              title: '写文章',
              url: '/article/create',
              icon: 'icofont-quill-pen',
              permission: ['article.create', 'article.store'].join(','),
            },
            {
              title: '标签',
              url: '/article/tag',
              icon: 'icofont-tags',
              permission: 'tag.index',
            },
          ],
        },
        {
          header: '消息',
          icon: 'icofont-google-talk',
          permission: [
            'illegal-info.index',
            'illegal-info.approve',
            'illegal-info.ignore',
            'comment.index',
            'comment.approve',
            'comment.reject',
            'notification.index',
            'notification.comments',
          ].join(','),
          subMenu: [
            {
              title: '举报信息',
              url: '/message/illegal-info',
              icon: 'icofont-email',
              permission: [
                'illegal-info.index',
                'illegal-info.approve',
                'illegal-info.ignore',
              ].join(','),
            },
            {
              title: '待审核评论',
              url: '/message/comments',
              icon: 'icofont-speech-comments',
              permission: [
                'comment.index',
                'comment.approve',
                'comment.reject',
              ].join(','),
            },
            {
              title: '通知',
              url: '/message/notification',
              icon: 'icofont-notification',
              permission: ['notification.index', 'notification.comments'].join(
                ','
              ),
            },
          ],
        },
        {
          header: '授权管理',
          icon: 'icofont-safety',
          permission: [
            'manager.index',
            'manager.update',
            'manager.create',
            'manager.destroy',
            'role.index',
            'role.create',
            'role.update',
            'role.destroy',
            'auth.index',
            'auth.create',
            'auth.update',
            'auth.destroy',
          ].join(','),
          subMenu: [
            {
              url: '/manager',
              title: '管理员列表',
              icon: 'icofont-user-alt-5',
              permission: [
                'manager.index',
                'manager.update',
                'manager.create',
                'manager.destroy',
              ].join(','),
            },
            {
              url: '/role',
              title: '角色列表',
              icon: 'icofont-restaurant-menu',
              permission: [
                'role.index',
                'role.create',
                'role.update',
                'role.destroy',
              ].join(','),
            },
            {
              url: '/auth',
              title: '权限列表',
              icon: 'icofont-lock',
              permission: [
                'auth.index',
                'auth.create',
                'auth.store',
                'auth.destroy',
              ].join(','),
            },
          ],
        },
        {
          header: '画廊',
          icon: 'icofont-pixels',
          permission: ['gallery.index', 'gallery.store'].join(','),
          subMenu: [
            {
              url: '/gallery',
              title: '查看图片',
              icon: 'icofont-image',
              permission: 'gallery.index',
            },
          ],
        },
        {
          header: '友情链接',
          icon: 'icofont-friendfeed',
          permission: [
            'friend-link.store',
            'friend-link.index',
            'friend-link.destroy',
            'friend-link.update',
          ].join(','),
          subMenu: [
            {
              title: '友链列表',
              url: '/friend-link',
              icon: 'icofont-sub-listing',
              permission: 'friend-link.index',
            },
          ],
        },
        {
          header: '敏感词汇',
          icon: 'icofont-text-height',
          permission: [
            'sensitive-word.index',
            'sensitive-word-category.index',
          ].join(','),
          subMenu: [
            {
              title: '分类列表',
              icon: 'icofont-archive',
              url: '/sensitive-word/category',
              permission: 'sensitive-word-category.index',
            },
            {
              title: '词汇列表',
              url: '/sensitive-word',
              icon: 'icofont-sub-listing',
              permission: 'sensitive-word.index',
            },
          ],
        },
        {
          header: '系统管理',
          icon: 'icofont-settings',
          permission: ['system-setting.index', 'system.log'].join(','),
          subMenu: [
            {
              title: '系统设置',
              url: '/system/setting',
              icon: 'icofont-settings-alt',
              permission: 'system-setting.index',
            },
            {
              title: '用户日志',
              url: '/system/user-log',
              icon: 'icofont-papers',
              permission: 'system.log',
            },
            {
              title: '任务日志',
              url: '/system/schedule-log',
              icon: 'icofont-restaurant-menu',
              permission: 'system.log',
            },
          ],
        },
        {
          header: '用户中心',
          icon: 'icofont-user-alt-5',
          permission: 'user.profile',
          subMenu: [
            {
              title: '账号设置',
              icon: 'icofont-settings-alt',
              url: '/profile',
              permission: 'user.profile',
            },
          ],
        },
      ],
      currentPathBack: '',
    }
  },
  computed: {
    currentPath() {
      return this.$route.path
    }
  },
  watch: {
    currentPath: {
      handler() {
        this.$nextTick(() => {
          const target = document.querySelector('.on')
        if (!target) {
          return
        }
        if (target.parentElement.dataset.direct === 'down') {
          return
        }
        this.execToggleAnimation(
          target.parentElement.parentElement.parentElement
        )
        })
      },
      immediate: true
    },
  },
  methods: {
    /**
     * toggle subMenu
     * @param {Event} $e
     */
    toggleMenu($e) {
      let target = $e.target
      while (target.tagName !== 'UL') {
        target = target.parentElement
      }

      this.execToggleAnimation(target)
    },
    /**
     * 获取子菜单
     *
     * @param {array} menus
     * @returns {array} filterd menu
     */
    filterMenu(menus) {
      return menus.filter((menu) => this.$can(menu.permission))
    },
    /**
     * 执行菜单栏动画
     * @param {Element} target
     */
    execToggleAnimation(target) {
      const arrow = target.querySelector('.icofont-caret-down')

      if (arrow.style.transform.indexOf('180') === -1) {
        arrow.style.transform = 'rotate(-180deg)'
      } else {
        arrow.style.transform = 'rotate(0)'
      }

      const subMenu = target.querySelector('.sub-menu')

      this.$animate(subMenu, {
        ani: 'slideToggle',
        easing: 'bezier(0.3, 0.3)',
        duration: 256,
      })
    },
  }
}
</script>

<style lang="scss">
.sidebar-visible{
  .sidebar{
    left: 0;
    position: relative;
  }
}
.sidebar-hidden{
  .sidebar{
    left: -29rem;
  }
}
.fix-sidebar{
  .sidebar{
    position: fixed;
  }
}
.sidebar {
  top: 0;
  z-index: 9;
  height: 100%;
  width: 28rem;
  padding-top: 9rem;
  padding-left: 1rem;
  box-sizing: border-box;
  background-color: #fff;
  transition: all .3s ease-out;

  overflow-y: auto;
  @extend %scroll-bar;
  /* width */

  ul {
    list-style: none;
    li {
      padding: 0.7rem 0.5rem;
      color: #666;
      word-break: keep-all;
      white-space: nowrap;
    }
    i {
      margin-right: 0.5rem;
    }
    .on {
      color: $blue;
    }
  }
  .header {
    color: #3f6ad8;
    font-size: 1.6rem;
    display: flex;
    justify-content: space-between;
    &:hover {
      cursor: pointer;
    }
    .icofont-caret-down {
      display: block;
      transition-duration: 256ms;
    }
  }
  .sub-menu {
    display: none;
    text-indent: 2em;
    overflow: hidden;
    font-size: 1.5rem;
    li {
      &:hover {
        border-radius: 0.3rem;
        background-color: #e6e6e6;
      }
    }
  }
  .sidebar-title{
    white-space: nowrap;
    word-break: break-all;
  }
}
</style>
