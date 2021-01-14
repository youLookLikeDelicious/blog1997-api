import {
    redirectToLogin
} from '~/router'
const state = function () {
    return {
        id: '',
        name: '',
        email: '',
        avatar: '',
        initialized: false, // 是否已经初始化
        authorities: []
    }
}

const mutations = {
    setUser(state, user) {
        state.initialized = true
        Object.assign(state, user)

        // 如果id为false，跳转到登陆页面
        if (!user.id) {
            redirectToLogin()
        }
    },
    /**
     * reset User avatar
     * @param {json} state 
     * @param {string} avatar 头像路由
     */
    setAvatar(state, avatar) {
        state.avatar = avatar
    },
    /**
     * reset User email
     * @param {json} state 
     * @param {string} avatar 头像路由
     */
    setEmail(state, email) {
        state.email = email
    },
    /**
     * reset User email
     * @param {json} state 
     * @param {string} avatar 头像路由
     */
    setName(state, name) {
      state.name = name
  },
}

export default {
    state,
    mutations,
    namespaced: true,
}
