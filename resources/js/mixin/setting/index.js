export default {
    methods: {
        /**
         * 设置配置信息
         * 
         * @param {json} conf
         */
        configSetting (conf) {
            if (!conf) {
                return
            }
            for (const key in conf) {
                if (conf[key]) {
                    this.model[key] = conf[key]
                }
            }
        },
        /**
         * 失败时恢复配置信息
         * 
         * @return {void}
         */
        restore () {
            this.configSetting(this.originModel)
        },
        /**
         * ajax请求成功之后的回调
         * 
         * @param response
         */
        responseCallback (response) {
            const data = response.data.data
            this.configSetting(data)
            this.originModel = data
        }
    }
}