/**
 * 将JSON转为FormData
 *
 * @param {JSON} data
 * @return {FormData}
 */
const json2FormData = (data) => {
    const formData = new FormData()

    for (let key in data) {
        if (data[key] instanceof Array) {
            const tempKey = key + '[]'
            for (let i = 0, len = data[key].length; i < len; i++) {
                if (data[key][i] === '') {
                    continue
                }
                formData.append(tempKey, data[key][i])
            }
            continue
        }
        
        formData.append(key, data[key])
    }

    return formData
}

export default {
    install(vue) {
        vue.prototype.$json2FormData = json2FormData
    }
}
