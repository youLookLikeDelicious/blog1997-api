describe('Test log', () => {
    before(() => {
        cy.intercept('post', 'https://www.blog1997.com/api/oauth/currentUser', {
            fixture: 'user'
        }).as('user')
    })

    it('Visit page', () => {
        cy.intercept('get', 'https://www.blog1997.com/admin/system/schedule-log', { fixture: 'log-schedule' }).as('log.schedule')
        cy.intercept('get', 'https://www.blog1997.com/api/admin/log', { fixture: 'log-user' }).as('log.user')
        cy.visit('https://www.blog1997.com/admin/system/user-log')
        cy.wait('@log.user')
        cy.wait(1000)

        // 查看任务日志
        cy.get('.sidebar a[href="/admin/system/schedule-log"]').click()
        cy.wait(1000)
    })
});
