const state = () => ({
    // 当前的专题
    topic: {
        id: '',
        name: ''
    },
    // 当前的文章
    article: {
        id: '',
        title: ''
    }
})

const mutations  ={
    setTopic (store, { id, name}) {
        store.topic = { id, name }
    },
    setArticle (store, { id, title }) {
        store.article = { id, title }
    }
}

export default {
    state,
    mutations,
    namespaced: true
}