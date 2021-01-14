/**
 * 测试文章列表
 */
export default function () {
    cy.get('.sidebar a[href="/admin/article"').click()
    cy.wait(1000)

    // 点击草稿列表
    cy.get('.article-tab > a').eq(1).click()
    cy.wait(1000)
    // 点击专题列表
    cy.get('.ui-select').click()
    cy.wait(1000)
    cy.get('.ui-select li').eq(2).click()
    cy.wait(1000)
}