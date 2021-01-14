import 'cypress-file-upload'
describe('Test sensitive word', () => {
    before(() => {
        cy.intercept('post', 'https://www.blog1997.com/api/oauth/currentUser', {
            fixture: 'user'
        }).as('user')
    })
    
    /**
     * 访问页面
     */
    it('Visit page', () => {
        cy.intercept('get', 'https://www.blog1997.com/api/admin/sensitive-word', { fixture: 'sensitive-word' }).as('sensitive.word')
        cy.visit('https://www.blog1997.com/admin/sensitive-word')
        cy.wait(['@user', '@sensitive.word'])
    })

    /**
     * 添加词汇
     */
    it('Test add word', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/sensitive-word', {fixture: 'sensitive-word-store'})
        cy.wait(300)
        cy.get('.tool-bar a[name="create"]').click()
        // ---- 填写敏感词
        cy.get('.tool-bar .create-sensitive-word .v-input-box').click()
        cy.wait(300)
        cy.get('.tool-bar .create-sensitive-word input').eq(0).type('不高兴')
        // ---- 选择分类
        cy.get('.tool-bar .create-sensitive-word .ui-select').click()
        cy.wait(300)
        cy.get('.tool-bar .create-sensitive-word .ui-select li').eq(1).click()
        cy.wait(300)
        // ---- 提交表单
        cy.get('.tool-bar .create-sensitive-word .icofont-upload-alt').click()
    })

    /**
     * 修改词汇
     */
    it('Test update word', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/sensitive-word/3290', {fixture: 'sensitive-word-update'}).as('sensitive.word.update')
        
        cy.get('.data-list .icofont-ui-edit').eq(0).click()
        // ---- 填写敏感词
        cy.get('.data-list .create-sensitive-word input').eq(0).clear()
        cy.wait(300)
        cy.get('.data-list .create-sensitive-word input').eq(0).type('没头脑')
        // ---- 选择分类
        cy.get('.data-list .create-sensitive-word .ui-select').click()
        cy.wait(300)
        cy.get('.data-list .create-sensitive-word .ui-select li').eq(2).click()
        cy.wait(300)
        // ---- 提交表单
        cy.get('.data-list .create-sensitive-word .icofont-upload-alt').click()
        cy.wait('@sensitive.word.update')
    })
    
    /**
     * 删除词汇
     */
    it('Test destroy word', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/sensitive-word/971', {fixture: 'sensitive-word-destroy'}).as('sensitive.word.destroy')
        cy.intercept('post', 'https://www.blog1997.com/api/admin/sensitive-word/batch-delete', { fixture: "sensitive-word-batch-destroy" }).as('sensitive.word.batch.destroy')
        cy.get('.data-list .icofont-ui-delete').eq(0).click()
        cy.wait('@sensitive.word.destroy')

        // ---- 批量删除
        cy.get('.data-list input[name=index]').eq(0).click()
        cy.get('.data-list input[name=index]').eq(1).click()
        cy.get('.data-list input[name=index]').eq(2).click()
        cy.get('.tool-bar .search-tools .btn-danger').scrollIntoView({ duration: 120 })
        cy.wait(300)
        cy.get('.tool-bar .search-tools .btn-danger').click({ force: true })
        cy.wait('@sensitive.word.batch.destroy')
        cy.wait(1000)
    })

    /**
     * 导入词汇
     */
    it('Test import word', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/sensitive-word/import', { fixture: 'sensitive-word-import' })
        cy.intercept('get', 'https://www.blog1997.com/api/admin/sensitive-word?category_id=12', { fixture: 'sensitive-word-search' }).as('sensitive.word.search')
        cy.get('.sensitive-word-menu .icofont-upload-alt').click()
        cy.wait(300)
        cy.get('.import-sensitive-word .ui-select').click()
        cy.wait(300)
        cy.get('.import-sensitive-word .ui-select li').eq(2).click()
        cy.get('.import-sensitive-word input[type=file]').attachFile('sensitive-word/word')
        cy.get('.import-sensitive-word .btn-enable').click('')
        cy.wait('@sensitive.word.search')
        cy.wait(1000)
    })
});
