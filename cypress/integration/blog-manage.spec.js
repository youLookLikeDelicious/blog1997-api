import testArticleListPage from './blog-manager/article-list'
import testCreateArticle from './blog-manager/article-create'
import testTopic from './blog-manager/article-topic'
import testTag from './blog-manager/article-tag';
beforeEach(() => {
    cy.on('window:confirm', () => true)
})
describe('test manage blog', () => {
    before(() => {
        cy.intercept('post', 'https://www.blog1997.com/api/oauth/currentUser', {
            fixture: 'user'
        }).as('user')

        cy.intercept('get', /https:\/\/www\.blog1997\.com\/api\/admin\/article\??$/, {
            fixture: 'article-list'
        }).as('article.list')
    })

    it('Visit blog manage page', () => {
        cy.visit('https://www.blog1997.com/admin/article')

        cy.wait(['@user'])
        cy.wait(1000)
    })

    it('Test article list and create article', () => {
        // 草稿列表
        cy.intercept('get', /https:\/\/www\.blog1997\.com\/api\/admin\/article\?type=draft$/, {
            fixture: 'article-list-draft'
        }).as('article.draft')
        cy.intercept('get', /https:\/\/www\.blog1997\.com\/api\/admin\/article\?topicId=17&type=draft$/, {
            fixture: 'article-list-topic-17'
        }).as('article.topic.17')
        // 文章列表
        cy.intercept('post', 'https://www.blog1997.com/api/admin/article', {
            fixture: 'article-store'
        }).as('article.store')
        // 新建文章需要的表单数据
        cy.intercept('get', 'https://www.blog1997.com/api/admin/article/create', {
            fixture: 'article-create'
        }).as('article.create')

        cy.intercept('get', /https:\/\/www\.blog1997\.com\/api\/admin\/article\??$/, {
            fixture: 'article-list'
        }).as('article.list')
        
        testArticleListPage()
        testCreateArticle()
    })

    /**
     * 测试专题的增删改查
     */
    it('Test topic', () => {
        cy.intercept('get', 'https://www.blog1997.com/api/admin/topic', {
            fixture: 'article-topic'
        }).as('article.topic')

        // 删除修改的相关路由
        cy.intercept({
            matchUrlAgainstPath: false,
            url: 'https://www.blog1997.com/api/admin/topic/25',
            method: 'post'
        }, (req) => {
            const response = req.body._method === 'DELETE' ? {
                fixture: 'article-topic-destroy'
            } : {
                fixture: 'article-topic-put'
            }
            req.reply(response)
        }).as('article.topic.post')

        // 新建的路由
        cy.intercept('post', 'https://www.blog1997.com/api/admin/topic', {
            fixture: 'article-topic-store'
        }).as('article.topic.store')

        testTopic()
    })

    it('Test tags', () => {
        cy.intercept('get', 'https://www.blog1997.com/api/admin/tag/create', {
            fixture: 'article-tag-create'
        }).as('article.tag.create')

        // 获取标签列表
        cy.intercept('get', 'https://www.blog1997.com/api/admin/tag?name=v', {
            fixture: 'article-tag-search-v'
        }).as('article.tag.search')
        cy.intercept('get', /https:\/\/www\.blog1997\.com\/api\/admin\/tag/, {
            fixture: 'article-tag'
        }).as('article.tag')

        cy.intercept('get', 'https://www.blog1997.com/image/tag/2020-12-22/201.701749001615fe16b55ab5449.42606432.jpg', {
            fixture: 'images/tags/front'
        })
        cy.intercept('get', 'https://www.blog1997.com/image/tag/2020-12-22/48.4479190016095fe16dee6d5bc7.92075244.png', {
            fixture: 'images/tags/vue'
        })
        cy.intercept('get', 'https://www.blog1997.com/image/tag/2020-12-22/218.413875001615fe1ddaf650e04.83086165.jpg', {
            fixture: 'images/tags/react'
        })

        // 删除修修改的路由
        cy.intercept({
            matchUrlAgainstPath: false,
            url: /https:\/\/www.blog1997\.com\/api\/admin\/tag\/16/,
            method: 'post'
        }, (req) => {
            const response = req.body._method === 'DELETE' ? {
                fixture: 'article-tag-destroy'
            } : {
                fixture: 'article-tag-put'
            }
            req.reply(response)
        }).as('article.tag.post')

        // 新建的路由
        cy.intercept('post', 'https://www.blog1997.com/api/admin/tag', {
            fixture: 'article-tag-store'
        }).as('article.tag.store')
        testTag()
    })
});
