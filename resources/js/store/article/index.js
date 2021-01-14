const state = () => ({
    publishedId: '',
    publishedTopic: '',
    publishedTitle: ''
})

const mutations  ={
    setPublishedArticle (store, { id, topic_id, title}) {
        if (store.publishedId !== id){
            store.publishedId = id
        }
        if (store.publishedTopic !== topic_id){
            store.publishedTopic = topic_id
        }
        if (store.publishedTitle !== title){
            store.publishedTitle = title
        }
    }
}

const getters = {
    publishedArticle: state => ({
        id: state.publishedId,
        topicId: state.publishedTopic,
        title: state.publishedTitle
    })
}

export default {
    state,
    getters,
    mutations,
    namespaced: true
}