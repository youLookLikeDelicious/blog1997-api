import {
    axios
} from '~/plugins/vendor/axios'

/**
 * 将auth转成树形结构
 */
const state = function () {
    return {
        selectedList: [], // 创建角色的时候使用
        auth: [],
        authBackUp: [], // 没有处理之前的权限列表
    }
}

/**
 * 获取被选中的auth
 * 
 * @param {array} authStack 
 * @param {array} auth 
 * @return {object} 当前选中的权限
 */
function getCurrentAuth(authStack, auth) {
    let i = 0
    const finder = item => item.id == authStack[i]

    let currentAuth = auth.find(finder)

    if (authStack.length === 1) {
        return currentAuth
    }

    ++i
    // 获取当前的auth
    for (const len = authStack.length; i < len; i++) {
        currentAuth = currentAuth.children.find(finder)
    }

    return currentAuth
}

const mutations = {
    /**
     * 转换权限的选中状态
     * 
     * @param {json} state 
     * @param {string} authPath
     * @return {void}
     */
    toggleAuth(state, authPath) {
        // 获取auth id
        const authStack = authPath
            .slice(0, -1)
            .split('_')
            .map(item => parseInt(item))

        // 获取当前选中的权限
        const currentAuth = getCurrentAuth(authStack, state.auth)

        const reg = new RegExp(`^${currentAuth.auth_path}.*`)

        // 获取当前权限以及子权限的id
        const selectedIds = state.authBackUp
            .filter(item => reg.test(item.auth_path))
            .map((item) => item.id)

        // 过滤出列表中已存在的元素 记作要移除的元素
        const existsIds = state.selectedList
            .filter((item) => selectedIds.indexOf(item) !== -1)


        const tempSelectedList = [...state.selectedList]


        // 过滤选项列表中已存在的id
        const notExistIds = tempSelectedList.filter((item) => existsIds.indexOf(item) === -1)
        // 过滤选中列表中重复的id
        const notExistIds2 = selectedIds.filter((item) => existsIds.indexOf(item) === -1)

        const result = notExistIds.concat(notExistIds2)


        // 判断当前的操作是取消操作还是选中操作 true选中 false取消选中
        const resultIncludesCurrentAuth = result.includes(currentAuth.id)

        // 获取所有的子权限
        const subAuth = getSubAuthIds(currentAuth, state.authBackUp)
        
        /**
         * 当前操作是 选中操作
         * 并且当前元素有父元素
         * 遍历所有的兄弟元素 判断父元素是否为选中状态
         * 
         * 遍历当前元素所有的子元素，将未被选中的子元素追加到result中
         */
        if (resultIncludesCurrentAuth) {
            let parentNode = currentAuth.parent
            // 修改父元素的选中状态
            while (parentNode) {
                // 获取兄弟元素
                const siblings = parentNode.children
                let parentSelectState = true
                for (let i = 0, len = siblings.length; i < len; i++) {
                    if (result.indexOf(siblings[i].id) === -1) {
                        parentSelectState = false
                        break
                    }
                }
                
                if (parentSelectState && !result.includes(parentNode.id)) {
                    result.push(parentNode.id)
                }
                parentNode = parentNode.parent
            }

            for (let i = 0, len = subAuth.length; i < len; i++) {
                if (!result.includes(subAuth[i])) {
                    result.push(subAuth[i])
                }
            }
        } else {
            // 当前操作时取消选中操作
            // 获取当前元素的所有子元素
            // 将子元素从选中列表中移除
            let subAuthIndex
            if (subAuth.length) {
                for (let i = 0, len = subAuth.length; i < len; i++) {
                    subAuthIndex = result.indexOf(subAuth[i])
                    if (subAuthIndex >= 0) {
                        result.splice(subAuthIndex, 1)
                    }
                }
            }

            // 修改其父元素的选中状态
            if (currentAuth.parent) {
                let parentAuthIndex
                for (let i = 0, len = authStack.length - 1; i < len; i++) {
                    parentAuthIndex = result.indexOf(authStack[i])
                    if (parentAuthIndex >= 0) {
                        result.splice(parentAuthIndex, 1)
                    }
                }
            }
        }

        state.selectedList = result
    },
    /**
     * 设置权限
     */
    setAuth(state, auth) {
        state.auth = auth
    },
    /**
     * 设置权限
     */
    setAuthBackUp(state, auth) {
        state.authBackUp = auth
    },
    /**
     * 清除所有的数据
     */
    clear (state) {
        state.selectedList = []
        state.auth = []
        state.authBackUp = []
    },
    /**
     * 设置被选中的权限
     * @param {json} state 
     * @param {array} authorities
     */
    setSelectedList (state, authorities) {
        if (!authorities || !authorities.length) {
            return
        }
        state.selectedList = authorities
    },
    /**
     * 清除选中的状态
     * @param {json} state 
     */
    clearSelectList (state) {
        state.selectedList.length = 0
    }
}

/**
 * 获取所有的子权限
 * 
 * @param {object} auth 父权限
 * @param {array} authList 
 * @return {array}
 */
const getSubAuthIds = (auth, authList) => {
    if (!auth.children || !authList.length) {
        return []
    }
    const children = []
    const reg = new RegExp(`^${auth.auth_path}.+`)

    for (let i = 0, len = authList.length; i < len; i++) {
        if (reg.test(authList[i].auth_path)) {
            children.push(authList[i].id)
        } else if (children.length) {
            break;
        }
    }

    return children
}

const actions = {
    /**
     * 获取权限，并将其转成树形结构
     * 
     * @param {function} param0 
     */
    getAuth({
        commit
    }) {
        axios.get("/admin/auth/create").then((response) => {
            const data = response.data.data;
            data.shift();
            let reducedData = [];
            commit('setAuthBackUp', data)
            const appendToParentAuth = (accumulator, auth) => {
                // 获取最后一个元素
                let currentAuth = accumulator[accumulator.length - 1];

                while (currentAuth) {
                    if (auth.parent_id === currentAuth.id) {
                        if (!currentAuth.children) {
                            currentAuth.children = [];
                        }

                        auth.parent = currentAuth
                        currentAuth.children.push(auth);
                        return accumulator;
                    }

                    if (!currentAuth.children) {
                        break;
                    }

                    currentAuth = currentAuth.children[currentAuth.children.length - 1];
                }

                accumulator.push(auth);
                return accumulator;
            };
            const reducer = (accumulator, currentValue, index) => {
                currentValue.selectedState = -1
                if (!index) {
                    accumulator.push(currentValue);
                    return accumulator;
                }

                const preIndex = reducedData.length - 1;

                appendToParentAuth(accumulator, currentValue);

                return accumulator;
            };

            data.reduce(reducer, reducedData);

            commit('setAuth', reducedData)
        });
    }
}

const getters = {
    getSubAuthIds: (state) => authPath => {
        const reg = new RegExp(`^${authPath}.+`)
        return state.authBackUp
            .filter((item) => reg.test(item.auth_path))
            .map(item => item.id)
    }
}
const namespaced = true

export default {
    state,
    actions,
    getters,
    namespaced,
    mutations,
}
