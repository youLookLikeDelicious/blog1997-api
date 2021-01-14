import 'cypress-file-upload'

/**
 * 测试标签页面
 */
export default function() {
    // 点击标签菜单 
    cy.get('.sidebar').find('a[href="/admin/article/tag"]').click()
    cy.wait('@article.tag')

    // 展开标签列表
    cy.wait(1000)

    // 新建标签
    cy.get('.tool-bar a[name="create"]').click()
    cy.wait(1000)
    // ----------输入 标签名称
    cy.get('.create-tag input').eq(0).type('New Tag')
    // ----------选择 父标签
    cy.get('.ui-select').click()
    cy.wait(1000)
    cy.get('.ui-select li').eq(1).click()
    // ----------添加 描述
    cy.get('.create-tag textarea').type('New tag description')
    // ----------选择封面
    cy.get('.create-tag input[type="file"]').attachFile('images/tags/react.jpg')
    cy.wait(1000)
    // ----------提交
    cy.get('.create-tag a.btn-enable').click()
    cy.wait(1000)

    // 修改标签
    cy.get('a.icofont-edit').eq(1).click()
    cy.wait(500)
    // ----- 修改标签名称
    cy.get('.create-tag input').eq(0).clear()
    cy.get('.create-tag input').eq(0).type('Vue3.0')
    // ----- 修改标签封面
    cy.get('.create-tag input[type="file"]').attachFile('images/tags/vue.png')
    cy.wait(1000)
    // ----- 修改描述
    cy.get('.create-tag textarea').clear()
    cy.get('.create-tag textarea').type('vue3.0 up up up')
    cy.get('.create-tag a.btn-enable').click()
    cy.wait(1000)

    // 删除标签
    // ----- 展开标签列表
    cy.get('.right-arrow').click()
    cy.wait(1000)
    cy.get('.right-arrow').click()
    cy.wait(1000)
    cy.get('a.icofont-delete').eq(1).click()
    cy.wait(1000)

    // 搜索标签
    cy.get('.search-tools input').click()
    cy.get('.search-tools input').type('v')
    cy.get('.search-tools .btn-enable').click()
    cy.wait(1000)
}