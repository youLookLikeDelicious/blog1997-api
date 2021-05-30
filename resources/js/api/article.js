import { axios } from '~/plugins/vendor/axios'

/**
 * 将文章作为微信公众号的图文素材
 * @param {int} id 
 * @returns {Promise}
 */
export function createWeChatMaterial(id) {
    return axios.post('/admin/article/create-wechat-material/' + id)
}