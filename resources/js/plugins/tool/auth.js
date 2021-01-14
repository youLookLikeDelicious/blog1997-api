import store from '~/store'

function can (permission) {
    if (! permission) {
        return false
    }

    permission = permission.split(',')
    
    const user = store.state.user
    for(let i = 0, len = permission.length; i < len; i++) {
        const finder = (auth) => auth.route_name.includes(permission[i])
        if (user.authorities.findIndex(finder) >= 0) {
            return true;
        }
    }

    return false
}

export { can }

/**
 * 指定角色
 * @param {string} roleName 
 * @return {boolean}
 */
function role (roleName) {
    const roles = store.state.user.roles

    for(let i = 0, len = roles.length; i < len; i++) {
        if (roles[i].name === roleName) {
            return true
        }
    }

    return false
}

export default {
    install (Vue) {
        Vue.prototype.$can = can
        Vue.prototype.$role = role
    }
}