import store from '~/store'

function setMessage ({ msg ='', status = true }) {
  store.commit("globalState/setPromptMessage", {
    msg,
    status,
  })
}

export default {
    install (Vue) {
      Vue.prototype.$showDialog = () => { store.commit("globalState/showDialog", true) }
      Vue.prototype.$hidDialog = () => { store.commit("globalState/showDialog", false) }
      Vue.prototype.$setMessage = setMessage
    }
  }
  