import { initFormula } from '@blog1997/vue-umeditor'

export default {
  install (vue) {
    vue.prototype.$initFormula = initFormula
  }
}
