import Vue from 'vue'

import axios from '~/plugins/vendor/axios'
import '~/plugins/register-components'
import store from '~/store'
import Auth from './Auth'
import animate from '@blog1997/animate'
import router from './router/auth'
import './plugins'

Vue.prototype.$animate = animate
Vue.use(axios)

const app = new Vue({
  store,
  router,
  render: h => h(Auth)
}).$mount('#app')
