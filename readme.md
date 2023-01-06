## Blog1997
  
一个现代化的博客系统,让摄影爱好者多一个选择。

[![codecov](https://codecov.io/gh/youLookLikeDelicious/blog1997-api/branch/master/graph/badge.svg?token=4GY5UED0WI)](https://codecov.io/gh/youLookLikeDelicious/blog1997-api)
![license](https://img.shields.io/github/license/youLookLikeDelicious/blog1997-api)

[接口文档](https://www.blog1997.com/docs/)
### 项目介绍
![introduce](https://img.wenhairu.com/images/2023/01/06/7pe0S.png)
Blog1997是一个前后端分离的博客系统。前端使用Nuxt，实现vue的服务端渲染（ssr）。后端使用Laravel + mysql + redis进行开发。该项目是系统的后端部分。

### 安装
请查看[文档](https://github.com/youLookLikeDelicious/blog1997-docker)

### 特性
- 服务端渲染。
- 自动生成sitemap.xml。
- 使用DFA算法对敏感词汇进行监测，将评论的敏感词汇替换成***，任意扩展词汇列表。
- 基于角色的权限控制（RBAC）。
- 比较全面的Feature测试和Unit测试
- 支持微信和GITHUB第三方登陆。
- 消息队列
- 支持高级数学公式的富文本
- 支持文章创建微信图文素材,自动更新到微信素材库。
- 相册管理。(完善中)
- 手册管理。(完善中)

### 感谢
- [公众图库免费图片托管](https://img.wenhairu.com/)
- [pixabay免费素材图库](https://pixabay.com/)
