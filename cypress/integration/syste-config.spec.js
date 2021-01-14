describe('Test config system', () => {
    before(() => {
        cy.intercept('post', 'https://www.blog1997.com/api/oauth/currentUser', {
            fixture: 'user'
        }).as('user')
    })
    
    /**
     * 访问页面
     */
    it('Visit page', () => {
        cy.intercept('get', 'https://www.blog1997.com/api/admin/system-setting', { fixture: 'system-setting' }).as('system.setting')
        cy.visit('https://www.blog1997.com/admin/system/setting')
        cy.wait(['@user', '@system.setting'])
    })

    /**
     * 测试开启|关闭评论
     * 测试开启|关闭 审核评论
     */
    it('Test toggle enable comment', () => {
        cy.wait(1000)
        cy.intercept('post', 'https://www.blog1997.com/api/admin/system-setting/1', (req) => {
            let fixture
            switch (JSON.stringify(req.body)) {
                case '{"enable_comment":"yes","verify_comment":"no","id":1,"_method":"PUT"}':
                        fixture = { fixture: 'system-setting-disable-comment' }
                        break
                case '{"enable_comment":"yes","verify_comment":"yes","id":1,"_method":"PUT"}':
                    fixture = { fixture: 'system-setting-enable-comment' }
                    break
                case '{"enable_comment":"no","verify_comment":"yes","id":1,"_method":"PUT"}':
                    fixture = { fixture: 'system-setting-disable-verify-comment' }
                    break
                case '{"enable_comment":"yes","verify_comment":"yes","id":1,"_method":"PUT"}':
                    fixture = { fixture: 'system-setting-enable-comment' }
                    break
            }
            console.log(fixture)
            req.reply(fixture)
        }).as('setting.update')

        cy.get('.v-switch').eq(0).click()
        cy.wait('@setting.update')
        cy.wait(1000)
        cy.get('.v-switch').eq(0).click()
        cy.wait('@setting.update')
        cy.wait(1000)
        cy.get('.v-switch').eq(1).click()
        cy.wait('@setting.update')
        cy.wait(1000)
        cy.get('.v-switch').eq(1).click()
        cy.wait('@setting.update')
        cy.wait(1000)
    })

    /**
     * 测试邮箱配置
     */
    it('Test config email', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/email-config/4', { fixture: 'email-config-update' }).as('email.config.update')
        cy.intercept('get', 'https://www.blog1997.com/api/admin/email-config', { fixture: 'email-config' })
        cy.get('li[data-name=EmailSetting]').click()
        cy.get('input[name=email]').clear()
        cy.get('input[name=email]').type('blog_1997@163.coms')
        
        // ------ 提交
        cy.get('.email-setting .btn-enable').click()
        cy.wait('@email.config.update')
        cy.wait(1000)
    })
});
