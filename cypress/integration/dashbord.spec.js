describe('The dashboard page', () => {
    before(() => {
        // root-level hook
        // runs before every test
    
        // dashboard url
        cy.intercept('get', 'https://www.blog1997.com/api/admin/dashboard', { fixture: 'dashboard' }).as('dashboard')
        cy.intercept('post', 'https://www.blog1997.com/api/oauth/currentUser', { fixture: 'user' }).as('user')
        // avatar url 会崩溃
        // cy.intercept('get', 'https://www.blog1997.com/image/avatar/2020-11-08/550.70846900165fa7a810acf834.76505998.jpg', { fixture: 'images/avatar.jpg' })

        // article-list url
    })
    it('Test dashboard', () => {
        
        cy.visit('https://www.blog1997.com/admin')
        cy.wait(['@user', '@dashboard'])
        cy.wait(1000)

        cy.get('#article-chart canvas').trigger('mouseover')
    })
});