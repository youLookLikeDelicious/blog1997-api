import 'cypress-file-upload'
describe('Test gallery', () => {
    before(() => {
        cy.intercept('post', 'https://www.blog1997.com/api/oauth/currentUser', {
            fixture: 'user'
        }).as('user')
        cy.intercept('get', 'https://www.blog1997.com/api/admin/gallery', { fixture: 'gallery' })
    })
    it('Visit gallery', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/gallery/36', { fixture: 'gallery-destroy' })
        cy.intercept('post', 'https://www.blog1997.com/api/admin/gallery', { fixture: 'gallery-store' }).as('gallery.store')
        cy.intercept('get', 'https://www.blog1997.com/image/gallery/2020-12-09/98.1580210016085fd08fa8269547.61618326.jpg', { fixture: 'images/gallery/1.jpg' })
        cy.intercept('get', 'https://www.blog1997.com/image/gallery/2020-12-09/228.087949001615fd08fc21579d0.79028584.jpg', { fixture: 'images/gallery/2.jpg' })
        cy.intercept('get', 'https://www.blog1997.com/image/gallery/2020-12-09/73.0739020016085fd09025120bb4.45066014.jpg', { fixture: 'images/gallery/3.jpg' })
        cy.intercept('get', 'https://www.blog1997.com/image/gallery/2020-12-09/893.460028001615fd09025705076.85434726.jpg', { fixture: 'images/gallery/4.jpg' })
        cy.intercept('get', 'https://www.blog1997.com/image/gallery/2020-12-09/181.903819001615fd09048dcaa24.27510566.jpg', { fixture: 'images/gallery/5.jpg' })
        cy.intercept('get', 'https://www.blog1997.com/image/gallery/2021-01-03/829.578764001615ff1dfaf8d4e60.42187602.jpg', { fixture: 'images/gallery/new-gallery.jpg' })
        cy.intercept('get', 'https://www.blog1997.com/image/gallery/2021-01-03/58.685566001615ff1dfb1a76071.41032210.jpg', { fixture: 'images/gallery/new-gallery-2.jpg' })
        cy.visit('https://www.blog1997.com/admin/gallery')

        // 放大图片
        cy.get('.gallery-image-list li').eq(1).click()
        cy.wait(300)
        cy.get('.big-image a').eq(2).click({ force: true })
        cy.wait(300)
        cy.get('.big-image a').eq(2).click({ force: true })
        cy.wait(300)
        cy.get('.big-image a').eq(2).click({ force: true })

        // 关闭放大显示对话框
        cy.get('.big-image a').eq(0).scrollIntoView({ duration: 210 })
        cy.wait(300)
        cy.get('.big-image a').eq(0).click({ force: true })

        // 添加图片
        cy.get('.tool-bar .icofont-upload-alt').click()
        cy.wait(300)
        cy.get('.upload-image-box input[type="file"]').attachFile('images/gallery/new-gallery.jpg')
        cy.get('.upload-image-wrap input[type="file"]').attachFile('images/gallery/new-gallery-2.jpg')
        cy.wait(300)
        // ----- 上传图片
        cy.get('.upload-image-wrap .icofont-upload-alt').click()
        cy.wait('@gallery.store')

        // 删除图片
        cy.get('.gallery-image-list li').eq(0).trigger('mouseenter')
        cy.wait(300)
        cy.get('.gallery-image-list li').eq(0).find('a').click({ force: true })
        cy.wait(1000)
    })
});
