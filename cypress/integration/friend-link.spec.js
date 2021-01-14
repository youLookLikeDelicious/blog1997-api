describe('Test friend link', () => {
    before(() => {
        cy.intercept('post', 'https://www.blog1997.com/api/oauth/currentUser', {
            fixture: 'user'
        }).as('user')
        cy.intercept('get', 'https://www.blog1997.com/api/admin/friend-link', { fixture: 'friend-link' })
    })

    it('Visit friend link', () => {
        cy.visit('https://www.blog1997.com/admin/friend-link')
    })

    /**
     * 测试添加友链
     */
    it('Test store new friend link', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/friend-link', { fixture: 'friend-link-store' })

        cy.get('.tool-bar a[name="create"]').click()
        cy.wait(300)
        // ----------- 填写名称
        cy.get('.tool-bar .create-friend-link input').eq(0).click()
        cy.wait(300)
        cy.get('.tool-bar .create-friend-link input').eq(0).type('Friend Link')
        cy.wait(300)
        // ----------- 填写地址
        cy.get('.tool-bar .create-friend-link input').eq(1).click()
        cy.wait(300)
        cy.get('.tool-bar .create-friend-link input').eq(1).type('https://www.blog1997.com')
        cy.get('.tool-bar .icofont-upload-alt').click()
    })

    /**
     * 测试更新友链信息
     */
    it('Test update friend link', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/friend-link/11', { fixture: 'friend-link-update' }).as('friend-link-update')
        cy.get('.data-list .icofont-edit').eq(0).click()
        cy.wait(300)

        // 修改名称
        cy.get('.data-list .create-friend-link input').eq(0).clear()
        cy.get('.data-list .create-friend-link input').eq(0).type('new name')
        // 修改连接地址
        cy.get('.data-list .create-friend-link input').eq(1).clear()
        cy.get('.data-list .create-friend-link input').eq(1).type('https://www.chaosxy.com')
        cy.get('.data-list .create-friend-link .icofont-upload-alt').click()
        cy.wait('@friend-link-update')
    })

    /**
     * 删除友链
     */
    it('Test destroy friend link', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/friend-link/11', { fixture: 'friend-link-destroy' }).as('friend-link-destroy')
        cy.get('.data-list .icofont-delete').eq(0).click()
        cy.wait('@friend-link-destroy')
    })

    /**
     * 测试查询友链
     */
    it('Test search friend link', () => {
        cy.intercept('get', 'https://www.blog1997.com/api/admin/friend-link?name=blog', { fixture: 'friend-link-search' }).as('friend.link.search')

        cy.get('.search-tools .v-input-box').click()
        cy.wait(300)
        cy.get('.search-tools input').type('blog')
        cy.get('.search-tools .btn-enable').click()
        cy.wait('@friend.link.search')
    })
});
