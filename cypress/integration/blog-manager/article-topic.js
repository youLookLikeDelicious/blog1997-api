// 专题列表相关测试
export default function () {
    // 点击专题菜单    
    cy.get('.sidebar > ul').eq(1).click()
    cy.get('.sidebar').find('a[href="/admin/article/topic"]').click()
    cy.wait('@article.topic')

    // 新建专题
    cy.get('.tool-bar a[name="create"]').click()
    cy.wait(1000)
    cy.get('.create-new-model-container input').type('New Topic')
    cy.wait(1000)
    cy.get('.create-new-model-container .icofont-upload-alt').click()
    cy.wait('@article.topic.store')
    
    // 修改专题
    cy.get('table.data-list .icofont-edit').eq(0).click()
    cy.wait(1000)
    cy.get('.data-list input').clear()
    cy.wait(1000)
    cy.get('.data-list input').type('Updated Topic')
    cy.wait(1000)
    cy.get('.data-list .icofont-upload-alt').click()

    // 删除专题
    cy.get('.data-list .icofont-delete').eq(0).click();

    cy.wait(1000)
}