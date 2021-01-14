// 全局组件 promptMessage的状态
const state = function () {
  return {
    prompt: [
      // {
      //   'message': '',
      //   'status': true
      // }
    ],
    waiting: false,
    hasCheckedUser: false,
    showDialog: false // 显示对话框
  }
}

const mutations = {
  // 设置数据的状态
  setPromptMessage (state, { msg, status = true }) {
    state.prompt.push({
      message: msg,
      status,
      symbol: Symbol()
    })
  },
  shiftPromptMessage (state) {
    state.prompt.shift()
  },
  // 设置等待的状态
  setWaitingState (state, flag) {
    if (state.waiting === flag) {
      return
    }
    state.waiting = flag
  },
  checkedUser (state) {
    state.hasCheckedUser = true
  },
  /**
   * 设置对话框的显隐
   * @param state
   * @param status
   */
  showDialog (state, status) {
    if (state.showDialog === status) {
      return
    }
    state.showDialog = status
  }
}

const namespaced = true
export default {
  state,
  namespaced,
  mutations
}
