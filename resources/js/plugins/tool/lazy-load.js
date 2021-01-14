import axios from 'axios'

/**
 * 定义observer的回调
 * @param {IntersectionObserverEntry} entries
 * @param {IntersectionObserver} observer
 */
const intersectionHandler = (entries, observer) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) {
        return
      }
      const target = entry.target
      target.src = target.dataset.src
      if (target.src.includes('image/article')) {
        // 请求未压缩的图片
        axios({
          url: target.src + '?t=origin',
          baseURL: '/',
          responseType: 'blob'
        }).then(response => response.data)
          .then((data) => {
            target.src = URL.createObjectURL(data)
          })
      }
      target.classList.remove('lazy')
      observer.unobserve(entry.target)
    })
  }
  
  /**
   * 懒加载图片
   * @returns {void}
   */
  const lazy = () => {
    const observer = new IntersectionObserver(intersectionHandler, {
      threshold: 0.02
    })
  
    const targets = document.querySelectorAll('.lazy')
    targets.forEach(target => observer.observe(target))
  }

  export default {
      install (vue) {
        vue.prototype.$lazy = lazy
      }
  }