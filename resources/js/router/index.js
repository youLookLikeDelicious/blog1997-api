import Vue from 'vue'
import store from '../store'
import { can } from '~/plugins/tool/auth'
import Index from '../page'
import VueRouter from 'vue-router'
import Article from '~/page/article'
import ArticleList from '~/components/article'
import ArticleCreate from '~/page/article/create'
import TopicList from '~/page/article/topic/index'
import TagList from '~/page/article/tag/index'
import Published from '~/page/published'

import Gallery from '~/page/gallery'
import FriendLink from '~/page/friend-link'
import Message from '~/page/message'
import Report from '~/page/message/report'
import VerifyComments from '~/page/message/comments'
import Notification from '~/page/message/notification'

import SensitiveWord from '~/page/sensitive-word'
import SensitiveWordCategory from '~/components/sensitive-word/category'
import SensitiveWordIndex from '~/components/sensitive-word'

import Manager from '~/page/authorize/manager'
import Auth from '~/page/authorize/auth'
import Role from '~/page/authorize/role'

import Setting from '~/page/system/setting'
import UserLog from '~/page/system/user-log'
import ScheduleLog from '~/page/system/schedule-log'

import Profile from '~/page/profile'

import PermissionDeny from '~/page/401'
import NotFound from '~/page/404'

import {
    axios
} from '~/plugins/vendor/axios'

Vue.use(VueRouter)


const routes = [{
        path: '/',
        name: 'Index',
        components: {
            default: Index
        },
        meta: {
            permission: 'admin.dashboard'
        }
    },
    // 文章相关路由
    {
        path: '/article',
        component: Article,
        children: [{
            path: 'create/:id?',
            component: ArticleCreate,
            name: 'article.create',
            meta: {
                permission: 'article.create,article.store'
            }
        }, {
            path: 'topic',
            component: TopicList,
            name: 'article.topic',
            meta: {
                permission: 'topic.store'
            }
        }, {
            path: 'tag',
            component: TagList,
            name: 'article.tag',
            meta: {
                permission: 'tag.index'
            }
        }, {
            path: ':id?',
            component: ArticleList,
            name: 'article.list',
            meta: {
                permission: 'article.create,article.store'
            }
        }]
    }, {
        path: '/published',
        name: 'published',
        component: Published,
        beforeEnter: (to, from, next) => {
            if (store.state.article.publishedId) {
                return next()
            }
            next({
                path: '/'
            })
        }
    }, {
        // 相册路由
        path: '/gallery',
        name: 'gallery',
        component: Gallery,
        meta: {
            permission: 'gallery.index'
        }
    }, {
        path: '/friend-link',
        name: 'friendLink',
        component: FriendLink,
        meta: {
            permission: 'friend-link.index'
        }
    }, {
        path: '/message',
        component: Message,
        children: [{
            path: 'illegal-info',
            component: Report,
            meta: {
                permission: 'illegal-info.index'
            }
        }, {
            path: 'comments',
            component: VerifyComments,
            meta: {
                permission: 'comment.index'
            }
        }, {
            path: 'notification',
            component: Notification,
            meta: {
                permission: 'notification.index'
            }
        }]
    }, {
        path: '/sensitive-word',
        component: SensitiveWord,
        children: [{
            path: 'category',
            component: SensitiveWordCategory,
            meta: {
                permission: 'sensitive-word-category.index'
            }
        }, {
            path: ':id?',
            component: SensitiveWordIndex,
            meta: {
                permission: 'sensitive-word.index'
            }
        }]
    }, {
        path: '/manager',
        component: Manager
    }, {
        path: '/auth',
        component: Auth,
        meta: {
            permission: 'auth.index'
        }
    }, {
        path: '/role',
        component: Role,
        meta: {
            permission: 'role.index'
        }
    }, {
        path: '/system/setting',
        component: Setting,
        meta: {
            permission: 'system-setting.index'
        }
    }, {
        path: '/system/user-log',
        component: UserLog,
        meta: {
            permission: 'system.log'
        }
    }, {
        path: '/system/schedule-log',
        component: ScheduleLog,
        meta: {
            permission: 'system.log'
        }
    }, {
        path: '/profile/:type?',
        component: Profile,
        meta: {
            permission: 'user.profile'
        }
    }, {
        path: '/401',
        component: PermissionDeny
    }, {
        path: '*',
        component: NotFound
    }
]

const router = new VueRouter({
    mode: 'history',
    // base: process.env.VUE_BASE_URL,
    base: '/admin',
    routes
})

/**
 * 重定向到登陆页面
 */
const redirectToLogin = () => {
    window.location.replace(`${process.env.MIX_SENTRY_DSN_PUBLIC}/admin/login`)
}

// 为路由设置前置守卫
router.beforeEach(async (to, from, next) => {
    // 用户的状态已经初始化过
    const user = await checkCurrentUser()

    if (store.state.globalState.showDialog) {
        store.commit('globalState/showDialog', false)
    }

    if (!user) {
        next(false)
        return redirectToLogin()
    }

    if (!checkAuthority(to)) {
        return next('/401')
    }
    
    specifyLayoutAndTile(to)
    next()
})

/**
 * 判断用户是否登陆
 * 
 * @return {Promise}
 */
function checkCurrentUser() {
    return new Promise(async (resolve, reject) => {
        if (store.state.user.initialized) {
            resolve(store.state.user.id)
        } else {
            // 1、尝试获取当前的用户
            const user = await axios.post('/oauth/currentUser')
                .then((response) => {
                    return response.data.data
                }).catch(() => false)

            if (user) {
                store.commit('user/setUser', user)
            }

            resolve(user.id)
        }
    })
}

/**
 * 指定title和layout
 * 
 * @param {Route} to
 * @return {void}
 */
function specifyLayoutAndTile(to) {
    // 判断路由组件是否定义模板，如果式，将模板添加到meta中
    to.matched.some(record => {
        let [layout, title] = [record.components.default.layout, record.components.default.title]

        to.meta.layout = layout || 'default'

        const currentTitle = document.head.querySelector('title').innerHTML
        if (!title) {
            setTitle(process.env.MIX_TITLE)
            return
        }

        if (currentTitle !== title) {
            setTitle(title)
        }
    })
}

/**
 * 检查用户权限
 * @param {Route} to
 * @return {boolean}
 */
function checkAuthority (to) {
    if (!to.meta.permission) {
        return true
    }

    return can(to.meta.permission)
}

/**
 * 设置页面的title
 * @param {string} title 
 */
function setTitle(title = '') {
    document.head.querySelector('title').innerHTML = title
}
export {
    redirectToLogin
}

export default router
