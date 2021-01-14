export default {
    install(vue) {
        vue.prototype.$verify = function (rule, target) {
            switch (rule) {
                case 'email':
                    return /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/.test(target)
                default:
                    return true
            }
        }
    }
}
