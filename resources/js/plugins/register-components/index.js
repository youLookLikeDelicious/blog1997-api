// 注册全局组件等
import Vue from 'vue'
import umeditor from '@blog1997/vue-umeditor'
import promptMessage from '~/components/global/prompt-message'
import waiting from '~/components/global/waiting'
import pagination from '~/components/global/pagination'
import baseComponent from '~/components/slot/base'
import VDialog from '~/components/slot/dialog'
import Select from '~/components/ui/select'
import CascadeSelect from '~/components/ui/cascade-select'
import VCheckbox from "~/components/global/checkbox";
import Avatar from '~/components/global/avatar'
import Switch from '~/components/ui/switch'
import Input from '~/components/ui/v-input'
import SubmitBtn from '~/components/global/submit-btn'
import SearchBtn from '~/components/global/search-btn'
import VHelper from '~/components/ui/v-helper'

Vue.use(umeditor)

Vue.use({
    install(Vue) {
        window.UMEDITOR_CONFIG.whiteList.script = ['src', 'onload']
        window.UMEDITOR_CONFIG.whiteList.div.push(...['v-for', ':key'])
        window.UMEDITOR_CONFIG.whiteList['transition-group'] = ['name', 'tag', 'class']
        window.UMEDITOR_CONFIG.whiteList.button = ['v-on:click']
        Vue.component('prompt-message', promptMessage)
        Vue.component('waiting', waiting)
        Vue.component('pagination', pagination)
        Vue.component('base-component', baseComponent)
        Vue.component('v-dialog', VDialog)
        Vue.component('v-select', Select)
        Vue.component('v-checkbox', VCheckbox)
        Vue.component('avatar', Avatar)
        Vue.component('CascadeSelect', CascadeSelect)
        Vue.component('v-switch', Switch)
        Vue.component('v-input', Input)
        Vue.component('submit-btn', SubmitBtn)
        Vue.component('search-btn', SearchBtn)
        Vue.component('v-helper', VHelper)
    }
})
