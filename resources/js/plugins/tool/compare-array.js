/**
 * 简单的数组比较
 * 
 * 不考虑元素为对象的情况
 * 
 * @param {array} expect 
 * @param {array} actual 
 * @return {boolean}
 */
const compareArray = function (expect, actual) {
    let [arr1, arr2] = [[...expect], [...actual]]
    const [str1, str2] = [arr1.sort().toString(), arr2.sort().toString()]

    return str1 === str2
}
export default {
    install (vue) {
        vue.prototype.$compareArray = compareArray
    }
}