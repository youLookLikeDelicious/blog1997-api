import Vue from 'vue'
import Vuex from 'vuex'

import user from './user'
import globalState from './global-state'
import article from './article'
import articleBread from './article/bread'
import auth from './auth'

// import changeAuthPlugin from './plugin/change-auth'

Vue.use(Vuex)

const store = new Vuex.Store({
    modules: {
        auth,
        user,
        article,
        globalState,
        articleBread,
    },
    // plugins: [changeAuthPlugin]
})

export default store
