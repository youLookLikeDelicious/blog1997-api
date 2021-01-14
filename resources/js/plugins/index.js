import Vue from 'vue'
import animate from './vendor/animate'
import axios from './vendor/axios'
import imagePreview from './vendor/image-preview'
import initFormula from './vendor/init-formula'

import helper from './tool/helper'
import compareArray from './tool/compare-array'
import checkElementAncestor from './tool/check-element-ancestors'

import './register-components'
import './filters'
import Verify from './verify'
import auth from './tool/auth'
import lazy from './tool/lazy-load';
import jsonToFormData from './tool/json-2-formdata';
Vue.use(animate)
Vue.use(axios)
Vue.use(imagePreview)
Vue.use(initFormula)
Vue.use(helper)
Vue.use(checkElementAncestor)
Vue.use(compareArray)
Vue.use(Verify)
Vue.use(auth)
Vue.use(lazy)
Vue.use(jsonToFormData)

