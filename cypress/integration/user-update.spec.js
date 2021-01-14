import 'cypress-file-upload'
describe('Test log', () => {
    before(() => {
        cy.intercept('post', 'https://www.blog1997.com/api/oauth/currentUser', {
            fixture: 'user'
        }).as('user')
    })

    it('Visit page', () => {
        cy.intercept('get', 'https://www.blog1997.com/api/user/profile', { fixture: 'user-profile' }).as('user.profile')
        cy.visit('https://www.blog1997.com/admin/profile')
        cy.wait('@user.profile')
        cy.wait(1000)
    })

    /**
     * 测试修改头像
     */
    it('Test change avatar', () => {
        cy.intercept('get', 'https://www.blog1997.com/image/avatar/2021-01-04/792.071792001615ff318151187d1.11539176.jpg', { fixture: 'images/new-avatar'})
        cy.intercept('post', 'https://www.blog1997.com/api/user/update/2', { fixture: 'user-change-avatar' }).as('user.change.avatar')

        cy.get('.profile input[type=file]').attachFile('images/new-avatar')
        cy.wait('@user.change.avatar')
        cy.wait(1000)
    })

    /**
     * 修改用户名
     */
    it('Test change name', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/user/update/2', { fixture: 'user-update-name' }).as('user.update.name')
        cy.get('.profile input[name=name]').clear()
        cy.get('.profile input[name=name]').type('不高兴')
        cy.get('.profile a[name=submit-name]').click()
        cy.wait('@user.update.name')
    })
    
    /**
     * 修改邮箱
     */
    it('Test change email', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/user/update/2', { fixture: 'user-update-email' }).as('user.update.email')
        cy.get('.profile input[name=email]').clear()
        cy.get('.profile input[name=email]').type('blog1997')
        cy.wait(1000)
        cy.get('.profile input[name=email]').type('blog1997@qq.com')
        cy.get('.profile a[name=submit-email]').click()
        cy.wait('@user.update.email')
        cy.wait(1000)
    })
})
