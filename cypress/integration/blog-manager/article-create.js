export default function () {
    // 点击新建文章
    cy.get('.article-list-header a[href="/admin/article/create"]').click()
    
    // 切换为富文本编辑器
    cy.get('.icofont-refresh').click()
    cy.get('.um_editor').should('be.exist')

    const editor = cy.get('.edui-body-container')
    editor.type('BLOG-1997 \n')
    
    // 添加公式
    cy.get('.edui-icon-formula').click()
    cy.get('.edui-dropdown-menu').should('have.css', 'display', 'block')
    cy.get('.edui-formula-latex-item').eq(1).click()

    // 插入代码
    cy.get('.edui-icon-code').click()
    cy.get('.code-content > textarea').type(`#include <iostream>
    #include<fstream>
    using namespace std;
     
    int main()
    {
        ifstream infile("14打印14的源代码.cpp",ios::binary);
        char ch;
        while(infile.peek()!=EOF)
        {
            infile.read(&ch,sizeof(ch));
            cout<<ch;
        }
        cout<<endl;
        return 0;
    }`)
    cy.get('.edui-btn-primary').click()
    
    cy.wait(1000)
    // 期望切换编辑器按钮 不存在
    cy.get('.icofont-refresh').should('not.exist')
    cy.get('.edui-body-container').clear()
    
    cy.get('.icofont-refresh').should('exist')
    cy.wait(1000)

    // 切换到markdown编辑器
    cy.get('.icofont-refresh').click()
    cy.get('.marked-editor').type('## BLOG-1997\n')
    cy.get('.marked-editor').type('```javascript\n')
    cy.get('.marked-editor').type('const i = 21\n')
    cy.get('.marked-editor').type('```\n')
    cy.get('.marked-editor').type('![](https://images.pexels.com/photos/110854/pexels-photo-110854.jpeg?cs=srgb&dl=pexels-miriam-espacio-110854.jpg&fm=jpg)\n')
    
    // 输入标题
    cy.get('.create-article header input').type('2020-12-12')

    // 发布文章
    cy.get('.publish-article a').eq(1).click()
    cy.wait(1000)

    // 点击专题选项
    cy.get('.ui-select').eq(0).click()
    // ---------选则第二个专题
    cy.get('.options-wrapper').eq(0).get('li').eq(1).click()

    cy.wait(1000)

    // 选择标签
    cy.get('.ui-select').eq(1).click()
    cy.get('.options-wrapper').eq(1).find('li').eq(1).click()
    cy.get('.options-wrapper').eq(1).find('li').eq(0).click()
    // ---------添加自定义标签
    cy.get('.ui-select').eq(1).find('input').type('new-tag')
    cy.get('.options-wrapper').eq(1).find('li').eq(0).click()

    cy.get('.dialog header').click()
    // 提交
    cy.get('.btn-enable').click()
    cy.wait('@article.store')
    cy.wait(1000)

    // 返回文章列表
    cy.get('.sub-container').eq(2).find('a').click()
    cy.wait(1000)
}