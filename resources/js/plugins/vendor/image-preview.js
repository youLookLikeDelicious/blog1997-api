// eslint-disable-next-line no-unused-vars
let createObjectURL = null
// 定义createObjectURL属性
if (window.createObjectURL !== undefined) {
  createObjectURL = window.createObjectURL
} else if (window.URL && window.URL.createObjectURL) {
  createObjectURL = window.URL.createObjectURL
} else if (window.webkitURL !== undefined) {
  createObjectURL = window.webkitURL.createObjectURL
} else {
  createObjectURL = null
}

/**
 * 为file对象创建相应的URL
 *
 * @param files
 * @returns {boolean|[]}
 */
function getObjectURL (files) {
  if (!createObjectURL) {
    return false
  }

  const result = []
  for (let i = 0, len = files.length; i < len; i++) {
    result.push(createObjectURL(files[i]))
  }

  return result
}
export default {
  install (Vue) {
    Vue.prototype.$getObjectURL = getObjectURL
  }
}
