// 判断一个元素是否是应一个元素是子元素
const checkElementAncestor = (children, parent) => {
    if (children === parent) {
        return true
    }

    let el = children.parentElement

    while (el && el.tagName !== 'BODY') {
        if (el === parent) {
            return true
        }
        el = el.parentElement
    }

    return false
}
export default {
    install (vue) {
        vue.prototype.$checkElementAncestor = checkElementAncestor
    }
}