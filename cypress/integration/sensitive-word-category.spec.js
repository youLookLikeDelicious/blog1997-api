describe('Test sensitive category', () => {
    before(() => {
        cy.intercept('post', 'https://www.blog1997.com/api/oauth/currentUser', {
            fixture: 'user'
        }).as('user')
        cy.intercept('get', 'https://www.blog1997.com/api/admin/sensitive-word-category', { fixture: 'sensitive-word-category' })
    })
    
    it('Visit sensitive word category', () => {
        cy.visit('https://www.blog1997.com/admin/sensitive-word/category')
    })
    
    /**
     * 添加分类
     */
    it('Test store new category', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/sensitive-word-category', { fixture: 'sensitive-word-category-store' }).as('sensitive-word-category-store')
        cy.get('.tool-bar a[name="create"]').click()

        cy.get('.sensitive-word-inline-form input').eq(0).click()
        cy.wait(300)
        // ------------- 编辑名称
        cy.get('.sensitive-word-inline-form input').eq(0).type('不喜欢')
        // ------------- 修改屏蔽级别
        cy.get('.sensitive-word-inline-form .ui-select').click()
        cy.wait(300)
        // ------------- 上传
        cy.get('.sensitive-word-inline-form .icofont-upload-alt').click()
        cy.wait('@sensitive-word-category-store')
        cy.wait(1000)
    })

    /**
     * 修改分类
     */
    it('Test modify category', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/sensitive-word-category/15', { fixture: 'sensitive-word-category-update' }).as('sensitive-word-category-update')
        cy.get('.data-list .icofont-ui-edit').eq(0).click()
        cy.wait(300)
        
        cy.get('.data-list .sensitive-word-inline-form input').eq(0).click()
        cy.wait(300)
        // ------------- 修改名称
        cy.get('.data-list .sensitive-word-inline-form input').eq(0).clear()
        cy.get('.data-list .sensitive-word-inline-form input').eq(0).type('不高兴')
        // ------------- 修改屏蔽级别
        cy.get('.sensitive-word-inline-form .ui-select').click()
        cy.get('.sensitive-word-inline-form .ui-select li').eq(1).click()
        cy.wait(300)
        // ------------- 上传
        cy.get('.data-list .sensitive-word-inline-form .icofont-upload-alt').click()
        cy.wait('@sensitive-word-category-update')
    })
    
    /**
     * 删除分类
     */
    it('Test destroy category', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/sensitive-word-category/15', { fixture: 'sensitive-word-category-destroy' }).as('sensitive-word-category-destroy')
        cy.get('.data-list .icofont-ui-delete').eq(0).click()
        cy.wait('@sensitive-word-category-destroy')
    })

    /**
     * 搜索分类
     */
    it('Test search', () => {
        cy.intercept('get', 'https://www.blog1997.com/api/admin/sensitive-word-category?name=%E9%AB%98%E5%85%B4&rank=2', { fixture: 'sensitive-word-category-search' })

        // ------ 输入关键字
        cy.get('.search-tools input').eq(0).type('高兴')
        // ------ 选择分类
        cy.get('.search-tools .ui-select').click()
        cy.wait(300)
        cy.get('.search-tools .ui-select li').eq(2).click()
        cy.get('.search-tools .btn-enable').click()
        cy.wait(1000)
    })
});
