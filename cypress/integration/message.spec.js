describe('Test message manager', () => {
    before(() => {
        // 载入页面
        cy.intercept('post', 'https://www.blog1997.com/api/oauth/currentUser', {
            fixture: 'user'
        }).as('user')

        cy.intercept('get', 'https://www.blog1997.com/api/admin/illegal-info', {
            fixture: 'illegal-info'
        }).as('illegal.info')
        cy.visit('https://www.blog1997.com/admin/message/illegal-info')
    })

    /**
     * 载入消息页面
     */
    it('Test illegal info', () => {
        cy.wait(['@user', '@illegal.info'])
    })

    /**
     * 测试忽略举报的记录
     */
    it('Test ignore report', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/illegal-info/ignore/5', {
            fixture: 'illegal-info-ignore'
        }).as('illegal.info.ignore')
        cy.get('.illegal-info-table .icofont-focus').eq(0).click()
        cy.wait('@illegal.info.ignore')
        cy.get('.illegal-info-table .icofont-focus').eq(0).trigger('mouseover')
    })

    /**
     * 测试通过举报的内容
     */
    it('Test approve report', () => {
        cy.intercept('post', 'https://www.blog1997.com/api/admin/illegal-info/approve/7', {
            fixture: 'illegal-info-approve'
        }).as('illegal-info-approve')
        cy.get('.illegal-info-table .icofont-court-hammer').eq(1).click()
        cy.wait('@illegal-info-approve')
    })

    /**
     * 测试需要验证的评论
     */
    it('Test comments', () => {
        cy.intercept('get', 'https://www.blog1997.com/api/admin/comment', {
            fixture: 'verify-comments'
        }).as('comments')
        cy.wait(1000)
        // 点击待审核评论菜单
        cy.get('.sidebar > ul').eq(2).find('a[href="/admin/message/comments"]').click()
        cy.wait('@comments')

        // 测试全选按钮
        cy.get('.xy-checkbox').click()
        cy.wait(1000)
        // ----------- 单击子选项
        cy.get('.comments-to-verify input[name="index"]').eq(0).click()
        cy.wait(1000)
        // ----------- 再次单击全选按钮
        cy.get('.xy-checkbox').click()
        cy.wait(1000)

        // 评论审批通过
        cy.intercept('post', 'https://www.blog1997.com/api/admin/comment/approve', {
            fixture: 'comment-approve'
        }).as('comment.approve')
        cy.get('.comments-to-verify .btn-enable').eq(0).click()
        cy.wait('@comment.approve')
        cy.wait(1000)
        // ------------批量通过
        cy.get('.comments-to-verify input[name="index"]').eq(0).click()
        cy.get('.comments-to-verify input[name="index"]').eq(1).click()
        cy.get('.comments-to-verify input[name="index"]').eq(2).click()
        cy.get('.comments-to-verify input[name="index"]').eq(3).click()
        cy.wait(1000)
        cy.get('.create-new-model-container .btn-enable').click()
        cy.wait('@comment.approve')

        // 测试评论审核不通过
        cy.intercept('post', 'https://www.blog1997.com/api/admin/comment/reject', {
            fixture: 'comment-reject'
        }).as('comment.reject')
        cy.wait(1000)
        cy.get('.xy-checkbox').click() // -- 全选
        cy.get('.create-new-model-container .btn-danger').click()
        cy.wait('@comment.reject')
    })

    /**
     * 测试通知的内容
     */
    it('Test notification', () => {
        cy.intercept('get', 'https://www.blog1997.com/api/admin/notification', {
            fixture: 'notification'
        }).as('notification')
        cy.get('.sidebar a[href="/admin/message/notification"]').click()
        cy.wait('@notification')

        // 回复评论
        cy.intercept('post', 'https://www.blog1997.com/api/comment', {fixture: 'comment-reply'}).as('comment.reply')
        cy.wait(1000)
        cy.get('.comment').trigger('mouseenter')
        cy.wait(1000)
        cy.get('.comment-box .icofont-speech-comments').click()
        // ------ 向下滚动页面
        cy.get('.comment-box .btn-wrapper a').eq(1).scrollIntoView()
        cy.wait(1000)
        cy.get('.edui-icon-emotion').click()
        cy.get('.edui-emotion-jd').eq(0).click()
        cy.wait(1000)
        cy.get('.edui-body-container').type('少林功夫好啊~')
        cy.wait(1000)
        cy.get('.comment-box .btn-wrapper a').eq(1).click()
        cy.wait('@comment.reply')
        cy.wait(1000)
    })
});
