import Vue from 'vue'
import VueRouter from 'vue-router'

import login from '~/auth-page/login'
import register from '~/auth-page/register'
import PasswordRest from '~/auth-page/password-reset'

Vue.use(VueRouter)

/**
 * 后台权限相关的路由
 */
const routes = [
    {
        path: '/login',
        name: 'Login',
        component: login
    }, {
        path: '/manager/register',
        name: 'Register',
        component: register
    }, {
        path: '/password/reset',
        name: 'PasswordRest',
        component: PasswordRest
    }
]

const router = new VueRouter({
    mode: 'history',
    // base: process.env.VUE_BASE_URL,
    base: '/admin',
    routes
})

export default router