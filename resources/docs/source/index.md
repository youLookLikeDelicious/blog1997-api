---
title: API Reference

language_tabs:
- javascript
- php

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.

<!-- END_INFO -->
### Blog1997接口文档
Blog1997是一个前后端分离的博客系统。前端使用Nuxt，实现vue的服务端渲染（ssr）,后端使用Laravel + mysql + redis进行开发。


#Admin dashboard


后台首页
<!-- START_43b55e3e80d28383edcda6f6f992094d -->
## Statistic site data

获取网站的统计信息

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/dashboard"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/dashboard',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "userInfo": [
            {
                "count": 200000,
                "type": 1
            },
            {
                "count": 110000,
                "type": 2
            }
        ],
        "articleInfo": [
            {
                "count": 3,
                "topic_id": 17,
                "topic": {
                    "id": 17,
                    "name": "ES7"
                }
            },
            {
                "count": 1,
                "topic_id": 21,
                "topic": {
                    "id": 21,
                    "name": "Docker"
                }
            }
        ],
        "illegalInfo": [],
        "totalLiked": 600,
        "totalCommented": 1600
    }
}
```

### HTTP Request
`GET api/admin/dashboard`


<!-- END_43b55e3e80d28383edcda6f6f992094d -->

#Article management


文章管理
<!-- START_4d5c7350ec672e48b09a66d7c114a534 -->
## Restore article from recycle bin

还原删除的文章
restore deleted article

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/article/restore/delectus"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/article/restore/delectus',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
null
```

### HTTP Request
`POST api/admin/article/restore/{article}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `article` |  optional  | 文章id

<!-- END_4d5c7350ec672e48b09a66d7c114a534 -->

<!-- START_81d1190f45b5f5c987bd653900f8416a -->
## Display article records

后台获取文章列表

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/article"
);

let params = {
    "topicId": "laudantium",
    "type": "est",
    "order-by": "excepturi",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/article',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'query' => [
            'topicId'=> 'laudantium',
            'type'=> 'est',
            'order-by'=> 'excepturi',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "pagination": {
            "total": 4,
            "uri": "?",
            "currentPage": 1,
            "next": null,
            "previous": null,
            "first": 1,
            "last": 1,
            "pages": 1
        },
        "records": [
            {
                "id": 137,
                "title": "测试框架Mocha使用",
                "is_origin": "yes",
                "visited": 0,
                "commented": 0,
                "liked": 0,
                "updated_at": "1615783476",
                "user_id": 2,
                "is_draft": "no"
            },
            {
                "id": 136,
                "title": "Webpack从入门到发布一个NPM包(一)",
                "is_origin": "yes",
                "visited": 0,
                "commented": 0,
                "liked": 0,
                "updated_at": "1615198588",
                "user_id": 2,
                "is_draft": "no"
            },
            {
                "id": 99,
                "title": "贪婪算法-回溯",
                "is_origin": "yes",
                "visited": 2,
                "commented": 0,
                "liked": 0,
                "updated_at": "1615115743",
                "user_id": 2,
                "is_draft": "no"
            },
            {
                "id": 100,
                "title": "初识Laravel Facades和Contract",
                "is_origin": "yes",
                "visited": 0,
                "commented": 0,
                "liked": 0,
                "updated_at": "1614766374",
                "user_id": 2,
                "is_draft": "no"
            }
        ],
        "topics": [
            {
                "id": 0,
                "name": "所有专题"
            },
            {
                "id": 17,
                "name": "ES7",
                "user_id": 2
            },
            {
                "id": 21,
                "name": "数据结构与算法",
                "user_id": 2
            }
        ],
        "currentTopicId": 0,
        "count": {
            "total": 4,
            "draft": 0,
            "deleted": 0
        }
    }
}
```

### HTTP Request
`GET api/admin/article`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `topicId` |  optional  | 专题id
    `type` |  optional  | 文章的类型,例如draft,deleted
    `order-by` |  optional  | 排序方式,例如hot,new,默认是new

<!-- END_81d1190f45b5f5c987bd653900f8416a -->

<!-- START_d25ebc07a54de65cbe2aeb9c2c562c5b -->
## Get topics and tags when publish article

获取创建文章所需要的数据:标签和专题

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/article/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/article/create',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "topics": [
            {
                "id": 17,
                "name": "ES7",
                "user_id": 2
            },
            {
                "id": 21,
                "name": "数据结构与算法",
                "user_id": 2
            }
        ],
        "tags": [
            {
                "id": 1,
                "name": "Tag"
            },
            {
                "id": 2,
                "name": "前端"
            },
            {
                "id": 3,
                "name": "Vue"
            },
            {
                "id": 15,
                "name": "React"
            },
            {
                "id": 16,
                "name": "后端"
            },
            {
                "id": 18,
                "name": "PHP7"
            },
            {
                "id": 19,
                "name": "Linux"
            }
        ]
    }
}
```

### HTTP Request
`GET api/admin/article/create`


<!-- END_d25ebc07a54de65cbe2aeb9c2c562c5b -->

<!-- START_cfa8b14ac76694abe891a11ba1e05358 -->
## Store newly created article

新建文章

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/article"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "tags": [
        "rerum"
    ],
    "title": "et",
    "content": "illo",
    "is_draft": "soluta",
    "topic_id": "animi",
    "is_markdown": "voluptatum",
    "cover": "aut"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/article',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'tags' => [
                'rerum',
            ],
            'title' => 'et',
            'content' => 'illo',
            'is_draft' => 'soluta',
            'topic_id' => 'animi',
            'is_markdown' => 'voluptatum',
            'cover' => 'aut',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "文章创建成功",
    "data": {
        "title": "Blog1997",
        "topic_id": 17,
        "is_origin": "yes",
        "order_by": "50",
        "content": "## 没头脑\n## 不高兴",
        "is_draft": "no",
        "is_markdown": "yes",
        "summary": "## 没头脑\n## 不高兴",
        "gallery_id": 28,
        "user_id": 2,
        "updated_at": "1618723068",
        "created_at": "1618723068",
        "id": 138,
        "tags": [
            {
                "id": 1,
                "name": "Tag",
                "pivot": {
                    "article_id": 138,
                    "tag_id": 1
                }
            },
            {
                "id": 2,
                "name": "前端",
                "pivot": {
                    "article_id": 138,
                    "tag_id": 2
                }
            }
        ]
    }
}
```

### HTTP Request
`POST api/admin/article`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `tags` | array |  required  | 标签列表
        `tags.*` | int|string |  required  | 当标签内容为字符串时,自动创建一个标签
        `title` | string |  required  | 文章的标题
        `content` | string |  required  | 文章内容
        `is_draft` | string |  required  | 是否是草稿,例如yes,no
        `topic_id` | string|int |  required  | 专题id或专题名称,当该值不为int类型时,会创建一个专题
        `is_markdown` | string |  required  | 是否是markdown格式
        `cover` | image|string |  optional  | required当该值为image的时候,自动上传图片,然后使用图片URL替换
    
<!-- END_cfa8b14ac76694abe891a11ba1e05358 -->

<!-- START_a040108113d8927b8e0b6af2c97765cb -->
## Show the specific article when update

获取文章的内容
如果文章不属于当前用户,无法获取文章详情

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/article/reiciendis"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/article/reiciendis',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "id": 136,
        "title": "Blog1997",
        "gallery_id": 26,
        "is_origin": "yes",
        "topic_id": 17,
        "is_markdown": "yes",
        "content": "Webpack是Javascrpit程序的一个静态模型捆绑器。",
        "order_by": 50,
        "article_id": 136,
        "user_id": 2,
        "is_draft": "no",
        "tags": [
            {
                "id": 2,
                "pivot": {
                    "article_id": 136,
                    "tag_id": 2
                }
            }
        ],
        "gallery": {
            "id": 26,
            "url": "\/image\/gallery\/2020-12-09\/73.0739020016085fd09025120bb4.45066014.jpg"
        }
    }
}
```

### HTTP Request
`GET api/admin/article/{article}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `article` |  required  | 文章的id

<!-- END_a040108113d8927b8e0b6af2c97765cb -->

<!-- START_3677bccdd0c5b6f0a0c7c4239ed66bb0 -->
## Update the specific article

更新文章|草稿,发布草稿

用户只能修改自己的文章
如果文章包含的图片被移除,尝试从本地存储中移除该图片

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/article/possimus"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "tags": [
        "iste"
    ],
    "title": "earum",
    "content": "repudiandae",
    "is_draft": "officiis",
    "topic_id": "ut",
    "is_markdown": "ut",
    "cover": "animi"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->put(
    'https://www.blog1997.com/api/admin/article/possimus',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'tags' => [
                'iste',
            ],
            'title' => 'earum',
            'content' => 'repudiandae',
            'is_draft' => 'officiis',
            'topic_id' => 'ut',
            'is_markdown' => 'ut',
            'cover' => 'animi',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "文章发布成功",
    "data": {
        "id": 138,
        "title": "Blog1997",
        "is_origin": "yes",
        "order_by": "50",
        "summary": "## 没头脑\n## 不高兴\nlong long ago, there is a cat",
        "content": "## 没头脑\n## 不高兴\nlong long ago, there is a cat",
        "article_id": 0,
        "liked": 0,
        "visited": 0,
        "commented": 0,
        "is_draft": "no",
        "is_markdown": "yes",
        "user_id": 2,
        "topic_id": 17,
        "gallery_id": 28,
        "created_at": "1618723068",
        "updated_at": "1618723505"
    }
}
```

### HTTP Request
`PUT api/admin/article/{article}`

`PATCH api/admin/article/{article}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `article` |  optional  | 文章id
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `tags` | array |  required  | 标签列表
        `tags.*` | int|string |  required  | 当标签内容为字符串时,自动创建一个标签
        `title` | string |  required  | 文章的标题
        `content` | string |  required  | 文章内容
        `is_draft` | string |  required  | 是否是草稿,例如yes,no
        `topic_id` | string|int |  required  | 专题id或专题名称,当该值不为int类型时,会创建一个专题
        `is_markdown` | string |  required  | 是否是markdown格式
        `cover` | image|string |  optional  | required当该值为image的时候,自动上传图片,然后使用图片URL替换
    
<!-- END_3677bccdd0c5b6f0a0c7c4239ed66bb0 -->

<!-- START_366a7514626581423b62f4b879f6b6ba -->
## Destroy the specific article

删除文章
如果删除回收站中的文章,将文章彻底移除,如果删除发布的文章,将文章移动到回收站
彻底删除的文章时,也会尝试删除本地保存的图片

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/article/rerum"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->delete(
    'https://www.blog1997.com/api/admin/article/rerum',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
null
```

### HTTP Request
`DELETE api/admin/article/{article}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `article` |  optional  | 文章id

<!-- END_366a7514626581423b62f4b879f6b6ba -->

<!-- START_531289109dfba66f21e6968bc1a1a0e1 -->
## Search article by tags

根据标签检索文章

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/article/tags"
);

let params = {
    "tag_id": "eaque",
    "p": "sit",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/article/tags',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'query' => [
            'tag_id'=> 'eaque',
            'p'=> 'sit',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "articles": [
            {
                "identity": "MTM3",
                "title": "测试框架Mocha使用",
                "is_origin": "yes",
                "user_id": 2,
                "is_markdown": "yes",
                "summary": "Mocha是一个功能丰富的javascript测试框架,运行在node.js和浏览器中，使异步测试变得简单有趣。为Javascript应用程序添加测试,不仅可以保证代码的质量，还可以获得一个像这样的小徽章 ![测试覆盖率报告](https:\/\/img.shields.io\/codecov\/c\/github\/youLookLikeDelicious\/animate?) 。除了Mocha，<a href=\"https:\/\/doc.ebichu.cc\/jest\/\" style=\"color: #00c6fb\" target=\"__blank\">Jest<\/a>、<a href=\"https:\/\/www.gitmemory.com\/avajs\/ava\" style=\"color: #00c6fb\" target=\"__blank\">Ava<\/a> 和 <a href=\"https:\/\/jasmine.github.io\/\" style=\"color: #00c6fb\" target=\"__blank\">Jasmine<\/a>等都是不错的选择。\n",
                "visited": 0,
                "gallery_id": 27,
                "commented": 0,
                "created_at": "1615702254",
                "updated_at": "1615783476",
                "author": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                },
                "gallery": {
                    "id": 27,
                    "url": "\/image\/gallery\/2020-12-09\/893.460028001615fd09025705076.85434726.jpg",
                    "thumbnail": null
                },
                "tags": [
                    {
                        "id": 2,
                        "name": "前端",
                        "pivot": {
                            "article_id": 137,
                            "tag_id": 2
                        }
                    }
                ]
            }
        ],
        "pages": 1,
        "p": 1,
        "articleNum": 3,
        "tags": [
            {
                "name": "前端",
                "id": 2
            },
            {
                "name": "Vue",
                "id": 3
            }
        ],
        "currentTagId": 2
    }
}
```

### HTTP Request
`GET api/article/tags`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `tag_id` |  optional  | 标签id
    `p` |  optional  | 请求的页数 默认是1

<!-- END_531289109dfba66f21e6968bc1a1a0e1 -->

<!-- START_99967aa92bb24dec8f5196f17c369921 -->
## Get comments about the specific article

获取文章的相关评论
并将文章的访问量+1

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/article/comments/nemo"
);

let params = {
    "p": "consequatur",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/article/comments/nemo',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'query' => [
            'p'=> 'consequatur',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "records": [
            {
                "id": 71,
                "content": "<p>123<\/p>",
                "user_id": 2,
                "level": 1,
                "reply_to": 2,
                "created_at": "1618655624",
                "liked": 0,
                "commented": 1,
                "thumbs": 0,
                "user": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                },
                "replies": [
                    {
                        "id": 72,
                        "level": 2,
                        "liked": 0,
                        "user_id": 2,
                        "content": "<p>23<\/p>",
                        "able_id": 71,
                        "root_id": 71,
                        "reply_to": 2,
                        "thumbs": 0,
                        "user": {
                            "id": 2,
                            "name": "不高兴",
                            "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                        },
                        "receiver": {
                            "id": 2,
                            "name": "不高兴",
                            "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                        }
                    }
                ]
            }
        ],
        "p": 1,
        "pages": 1
    }
}
```

### HTTP Request
`POST api/article/comments/{articleId}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `articleId` |  required  | The id of article
#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `p` |  optional  | 请求的页数 默认是1

<!-- END_99967aa92bb24dec8f5196f17c369921 -->

<!-- START_af4200a7dbd9c40e52852180ec70b137 -->
## Search article

搜索文章

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/article/search"
);

let params = {
    "order_by": "nemo",
    "tag_id": "officiis",
    "keyword": "a",
    "p": "cupiditate",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/article/search',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'query' => [
            'order_by'=> 'nemo',
            'tag_id'=> 'officiis',
            'keyword'=> 'a',
            'p'=> 'cupiditate',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "articles": [
            {
                "identity": "MTM3",
                "title": "测试框架Mocha使用",
                "is_origin": "yes",
                "user_id": 2,
                "is_markdown": "yes",
                "summary": "Mocha是一个功能丰富的javascript测试框架,运行在node.js和浏览器中，使异步测试变得简单有趣。为Javascript应用程序添加测试,不仅可以保证代码的质量，还可以获得一个像这样的小徽章 ![测试覆盖率报告](https:\/\/img.shields.io\/codecov\/c\/github\/youLookLikeDelicious\/animate?) 。除了Mocha，<a href=\"https:\/\/doc.ebichu.cc\/jest\/\" style=\"color: #00c6fb\" target=\"__blank\">Jest<\/a>、<a href=\"https:\/\/www.gitmemory.com\/avajs\/ava\" style=\"color: #00c6fb\" target=\"__blank\">Ava<\/a> 和 <a href=\"https:\/\/jasmine.github.io\/\" style=\"color: #00c6fb\" target=\"__blank\">Jasmine<\/a>等都是不错的选择。\n",
                "visited": 0,
                "gallery_id": 27,
                "commented": 0,
                "created_at": "1615702254",
                "updated_at": "1615783476",
                "author": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                },
                "gallery": {
                    "id": 27,
                    "url": "\/image\/gallery\/2020-12-09\/893.460028001615fd09025705076.85434726.jpg",
                    "thumbnail": null
                },
                "tags": [
                    {
                        "id": 2,
                        "name": "前端",
                        "pivot": {
                            "article_id": 137,
                            "tag_id": 2
                        }
                    }
                ]
            },
            {
                "identity": "MTM2",
                "title": "Webpack从入门到发布一个NPM包(一)",
                "is_origin": "yes",
                "user_id": 2,
                "is_markdown": "yes",
                "summary": "Webpack是Javascrpit程序的一个静态模型捆绑器。当webpack处理你的应用程序时，它再内部建立了一个依赖关系图，映射了项目中依赖的每个模块，并生成多个捆绑包。  \n在模块化设计中，开发者将程序拆散成离散的功能块，称之为模块。每个模块都有自己独立的作用域，方便验证、调试和测试。编写良好的模块，可以提供更好的抽象和封装，这样在应用程序中的每个模块都有一致的设计原则和明确的目的。\n",
                "visited": 0,
                "gallery_id": 26,
                "commented": 0,
                "created_at": "1615118758",
                "updated_at": "1615198588",
                "author": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                },
                "gallery": {
                    "id": 26,
                    "url": "\/image\/gallery\/2020-12-09\/73.0739020016085fd09025120bb4.45066014.jpg",
                    "thumbnail": null
                },
                "tags": [
                    {
                        "id": 2,
                        "name": "前端",
                        "pivot": {
                            "article_id": 136,
                            "tag_id": 2
                        }
                    }
                ]
            }
        ],
        "pages": 1,
        "p": 1,
        "articleNum": 2
    }
}
```

### HTTP Request
`POST api/article/search`

`GET api/article/search`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `order_by` |  optional  | 排序的方式
    `tag_id` |  optional  | 标签ID
    `keyword` |  optional  | 关键字
    `p` |  optional  | 请求的页数 默认是1

<!-- END_af4200a7dbd9c40e52852180ec70b137 -->

<!-- START_b745a3237360fdd02c18c4da9305a174 -->
## Show the specific article in front page
获取文章详情

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/article/aut"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/article/aut',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "article": {
            "identity": "MTM2",
            "content": "Webpack是Javascrpit程序的一个静态模型捆绑器。当webpack处理你的应用程序时，它再内部建立了一个依赖关系图，映射了项目中依赖的每个模块，并生成多个捆绑包。  \n在模块化设计中，开发者将程序拆散成离散的功能块，称之为模块。每个模块都有自己独立的作用域，方便验证、调试和测试。编写良好的模块，可以提供更好的抽象和封装，这样在应用程序中的每个模块都有一致的设计原则和明确的目的。\n<!-- more -->\n## 一、前言\nwebpack也用了一段时间了，在这里总结一下webpack的知识点。这个系列主要分为一下三个部分\n1. [在本地使用webpack搭建一个项目](\/)\n2. 测试本地项目，并发布一个NPM包\n3. 使用GITHUB的Action，自动发布NPM包\n\n## 二、准备工作\n+ 安装 <a href=\"https:\/\/nodejs.org\/zh-cn\/download\/\" style=\"color: #00c6fb\" target=\"__blank\">noode<\/a>\n+ 新建一个文件夹，作为项目的根目录，以blog1997为例  \n然后在命令行（CMD）中，进入项目根目录，初始化项目\n```bash\nnpm init\n```\n之后，根据提示，输入相关信息\n```\nThis utility will walk you through creating a package.json file.\nIt only covers the most common items, and tries to guess sensible defaults.\n_\nSee `npm help init` for definitive documentation on these fields\nand exactly what they do.\n_\nUse `npm install <pkg>` afterwards to install a package and\nsave it as a dependency in the package.json file.\n_\nPress ^C at any time to quit.\npackage name: (blog1997)                      \/\/ 项目名称，默认是文件夹名称，回车即可\nversion: (1.0.0)                              \/\/ 项目的版本号，默认1.0.0，回车即可\ndescription:                                  \/\/ 项目的描述，默认是空字符串，回车即可\nentry point: (index.js)                       \/\/ NPM包的入口文件，会在下一章介绍，回车即可\ntest command:                                 \/\/ 测试命令，回车即可\ngit repository:                               \/\/ github仓库的地址，回车即可\nkeywords:                                     \/\/ 项目的关键字，回车即可\nauthor:                                       \/\/ 作者信息，回车即可\nlicense: (ISC)                      \n```\n然会在项目的根目录下，会创建一个package.json文件，在这里，你可以随意修改项目的相关配置。\n```json\n{\n  \"name\": \"blog1997\",  \/\/ 对应之前输入的项目名称\n  \"version\": \"1.0.0\",  \/\/ 对应之前输入的version:(1.0.0)\n  \"description\": \"\",   \/\/ 对应之前输入的description\n  \"main\": \"index.js\",  \/\/ 对应之前输入的entry point\n  \"scripts\": {\n    \"test\": \"echo \\\"Error: no test specified\\\" && exit 1\"\n  },\n  \"author\": \"\",        \/\/ 对应之前输入的author\n  \"license\": \"ISC\"\n}\n```\n+ 在项目根目录中创建一个文件夹**src**，并创建**index.js**文件\n## 三、安装webapck\n```bash\nnpm i --save-dev webpack webpack-cli webpack-dev-server\n```\n目前使用的版本是webpack ^5.18.0, webpack-cli ^4.4.0, webpack-dev-server ^3.11.2\n+ <a href=\"https:\/\/github.com\/webpack\/webpack-cli#readme\" style=\"color: #00c6fb\" target=\"__blank\">webpack-cli<\/a> 为开发者提供了一些灵活的命令,加速开发者自定义webpack项目\n+ <a href=\"https:\/\/github.com\/webpack\/webpack-dev-server#readme\" style=\"color: #00c6fb\" target=\"__blank\">webpack-dev-server<\/a> 提供了一个实时重载的开发服务器,但只能在开发过程中使用  \n+ --save-dev表示实在开发模式中使用的包，npm会将包的信息放在**package.json**文件中的**devDependencies**属性中，默认值是--save，包的信息会存放在**dependencies**属性中。  \n然后配置webpack,在项目的根目录中,创建一个文件**webpack.config.js**(名字可以自定义)\n```javascript\nconst path = require(\"path\");\nmodule.exports = {\n    mode: 'development',                         \/\/ 设置为开发模式, 值:development|production\n    entry: \".\/src\/index.js\",                     \/\/ 项目模块的入口(索引)文件\n    output: {                                    \/\/ 输出打包模块的配置\n        path: path.resolve(__dirname, \"dist\"),   \/\/ 表示项目根目录下的dist文件夹\n        filename: \"bundle.js\"\n    },\n    devServer: {\n        port: 8081          \/\/ 可以自定义服务器端口\n    }\n}\n```\n## 四、安装SASS\n```bash\nnpm install --save-dev style-loader css-loader sass-loader sass\n```\n+  <a href=\"https:\/\/github.com\/sass\/dart-sass\" style=\"color: #00c6fb\" target=\"__blank\">sass<\/a> 是Dart sass的发行版。提供了一些Node.js API和一些命令行命令。\n+  <a href=\"https:\/\/github.com\/webpack-contrib\/sass-loader\" style=\"color: #00c6fb\" target=\"__blank\">sass-loader<\/a> 将scss或sass文件编译成css\n+ <a href=\"https:\/\/github.com\/webpack-contrib\/css-loader\" style=\"color: #00c6fb\" target=\"__blank\">css-loader<\/a> 将css解析到JavaScript代码中，并处理其中的相关依赖\n+ <a href=\"https:\/\/github.com\/webpack-contrib\/style-loader\" style=\"color: #00c6fb\" target=\"__blank\">style-loader<\/a> 将css注入到DOM中的Style标签里  \n然后配置**webpack.config.js**\n```javascript\nmodule.exports = {\n    entry: \".\/src\/index.js\",\n    module: {\n        rules: [{\n            test: \/\\.s?[ac]ss$\/,         \/\/ 匹配css、scss和sass文件的正则\n            use: [{\n                loader: 'style-loader'\n            }, {\n                loader: 'css-loader'\n            }, {\n                loader: 'sass-loader'\n            }]\n        }],\n    },\n}\n```\n## 五、安装babel\n```node\nnpm install --save-dev @babel\/preset-env babel-loader @babel\/core\n```\n目前使用的版本是：@babel\/core ^7.12.10，@babel\/preset-env，^7.12.11，babel-loader，^8.2.2  \n配置一下babel,在项目根目录中创建文件 **.babel.json**\n```json\n{\n  \"presets\": [\n    [\n      \"@babel\/preset-env\",\n      {\n        \"targets\": {\n          \"ie\": \"10\",\n          \"chrome\": \"67\"\n        },\n        \"useBuiltIns\": \"usage\",\n        \"corejs\": \"3.6.5\"\n      }\n    ]\n  ]\n}\n```\nuseBuiltIns: \"entry\" | \"usage\" | false，默认是false，这个选项用于配置 **@babel\/preset-env**是怎么处理 <a href=\"https:\/\/developer.mozilla.org\/zh-CN\/docs\/Glossary\/Polyfill\" target=\"blank\" style=\"font-weight: bold\">polyfills<\/a>的  \nentry: 会根据targets环境的不同，将 **import \"core-js\/stable;\"**和 **import \"regenerator-runtime\/runtime\"** 声明替换为特定的import  \nusage: 在每个文件中为polyfill添加特定的导入。相同的polyfill只会添加一次。  \nfalse：不自动添加polyfill\n查看更多@babel\/preset-env的 <a href=\"https:\/\/github.com\/webpack-contrib\/style-loader\" style=\"color: #00c6fb\" target=\"__blank\">配置<\/a>  \n配置**webapck.config.js**\n```javascript\nmodule.exports = {\n    ...\n    module: {\n        rules: [{\n            test: \/\\.?js$\/,                              \/\/ 匹配js文件的正则表达式\n            exclude: \/(node_modules|bower_components)\/,  \/\/ 不对node_modules和bower_components文件夹下的js文件进行处理\n            use: {\n                loader: 'babel-loader',\n                options: {\n                    presets: ['@babel\/preset-env']\n                }\n            }\n        }\n}\n```\n## 六、html-webpack-plugin\n之前安装的babel和sass都属于webpack的loader范畴，用于转换某些特定类型的模块。而插件执行的任务更加广泛，包括：打包优化，资源管理，注入环境变量。\n```bash\nnpm install html-webpack-plugin --save-dev\n```\n目前使用的版本是5.2.0。然后在项目根目录中新建一个文件**app.html**\n```html\n<!DOCTYPE html>\n<html lang=\"zh\">\n<head>\n    <meta charset=\"UTF-8\">\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n    <title><%= htmlWebpackPlugin.options.title %><\/title>\n<\/head>\n<body><\/body>\n<\/html>\n```\n**<%= htmlWebpackPlugin.options.title %>**会读取webpack.config.js配置文件中的Title属性。\n```javascript\nconst HtmlWebpackPlugin = require('html-webpack-plugin')\nmodule.exports = {\n    ...\n    plugins: [\n        new HtmlWebpackPlugin({\n            title: 'Blog1997',   \/\/ 标题\n            template: 'app.html' \/\/ 模板文件路径\n        })\n    ]\n}\n```\n修改**package.json**\n```json\n{\n  \"name\": \"blog1997\",\n  \"version\": \"1.0.0\",\n  \"description\": \"\",\n  \"main\": \"index.js\",\n  \"scripts\": {\n    \"dev\": \"webpack serve --config webpack.config.js\"\n  }\n  ...\n}\n```\n运行 **npm run dev** 就可以在本地打开开发服务器了，我们之前创建的**src\/index.js**会被webapck打包，并添加到模板中。如下图所示\n\n![webpack-dev-server](\/image\/article\/2021-03-08\/459.5133360016260458fe97d5487.53571476.png?width=1919&height=1039)\n## 七、file-loader\n```bash\nnpm install --save-dev file-loader\n```\nfile-loader可以帮助你打包一些文件，如JPG、PNG和TTF等。  \n配置**webpack.config.js**\n```javascript\nmodule.exports = {\n    ...\n    module: {\n      rules: [\n       {\n         test: \/\\.(png|svg|jpg|gif)$\/,  \/\/ 匹配文件的正则表达式\n         use: [\n           {\n            loader: 'file-loader?name=.\/img\/[name].[ext]'\n           }\n         ]\n       }\n      ]\n    }\n  }\n```\n查看更多关于file-loader的 <a href=\"https:\/\/github.com\/webpack-contrib\/file-loader\" style=\"color: #00c6fb\" target=\"__blank\">配置<\/a>\n## 八、分割JS代码\n在中型或大型项目中，前端的代码量也是非常大的。如果将所有的JS代码打包在一个文件中，文件的加载时非常慢的，用户的初次体验也不是很友好。所以，我们在这种情况下，需要分割代码，将代码打包在不同的文件中，按需加载。\n```bash\nnpm install @babel\/plugin-syntax-dynamic-import --save-dev\n```\n修改 **.babel.json** 配置\n```json\n{\n  \"presets\": [\n    [\n      \"@babel\/preset-env\",\n      {\n        \"targets\": {\n          \"ie\": \"10\",\n          \"chrome\": \"67\",\n        },\n        \"useBuiltIns\": \"usage\",\n        \"corejs\": \"3.6.5\"\n      }\n    ]\n  ],\n  \"plugins\": [\"@babel\/plugin-syntax-dynamic-import\"]\n}\n```\n修改 **webapck.config.js** 配置\n```javascript\nconst path = require(\"path\");\nmodule.exports = {\n    mode: 'development',\n    entry: \".\/src\/index.js\",\n    output: {\n        path: path.resolve(__dirname, \"dist\"),\n        filename: \"[name].bundle.js\"\n    }\n}\n```\n修改代码\n```javascript\n\/\/ chat.js\nexport default {\n    say () {\n       \/\/  ...\n    }\n}\n\n\/\/ main.js\nbutton.onclick = () => {\n    import(\/* webpackChunkName: 'dynamic' *\/ '.\/chat.js').then(chat=> {\n        chat.default.say()\n    })\n}\n```\n## 九、clean-webpack-plugin\n在使用webpack-server服务或打包之前，清空dist文件夹\n```bash\nnpm i --save-dev clean-webpack-plugin\n```\n修改 **webpack.config.js**\n```javascript\nconst path = require(\"path\");\nconst { CleanWebpackPlugin } = require('clean-webpack-plugin')\nmodule.exports = {\n    mode: 'development',\n    ...\n    plugins: [\n        new CleanWebpackPlugin({\n            cleanStaleWebpackAssets: true\n        })\n    ]\n}\n```\n## 十、uglify js Webpack plugin\n用于压缩JS代码，并去掉注释部分\n```bash\nnpm install uglifyjs-webpack-plugin --save-dev\n```\n配置 **webpack.config.js**\n```javascript\nconst UglifyJsPlugin = require('uglifyjs-webpack-plugin')\nmodule.exports = {\n    mode: 'development',\n    ...\n    optimization: {\n        minimizer: [new UglifyJsPlugin({\n            uglifyOptions: {\n                output: {\n                    comments: false,\n                    beautify: false\n                },\n                compress: true,\n                warning: false\n            }\n        })]\n    }\n}\n```\n## 十一、其他小技巧\n手动指定loader\n```javascript \nimport styleContent from 'file-loader!extract-loader!css-loader!.\/mathquill.css'\n```\n\n![](\/image\/article\/2021-03-08\/595.351933001626045f94555ecf9.00956441.png?width=1918&height=935)\n\n如果有什么意见或建议，欢迎留言讨论。",
            "user_id": 2,
            "title": "Webpack从入门到发布一个NPM包(一)",
            "gallery_id": 26,
            "visited": 0,
            "commented": 0,
            "liked": 0,
            "created_at": "1615118758",
            "is_markdown": "yes",
            "updated_at": "1615198588",
            "author": {
                "id": 2,
                "name": "不高兴",
                "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
            },
            "gallery": {
                "id": 26,
                "url": "\/image\/gallery\/2020-12-09\/73.0739020016085fd09025120bb4.45066014.jpg",
                "thumbnail": null
            },
            "thumbs": false,
            "tags": [
                {
                    "id": 2,
                    "name": "前端",
                    "pivot": {
                        "article_id": 136,
                        "tag_id": 2
                    }
                }
            ]
        },
        "commented": 0,
        "comments": []
    }
}
```

### HTTP Request
`GET api/article/{articleId}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `articleId` |  required  | The id of article

<!-- END_b745a3237360fdd02c18c4da9305a174 -->

#Comment management


评论管理
<!-- START_ec795c34741d7b05102699cf4fc1150a -->
## Approve submit comment

评论通过审核
Set comment be verified .

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/comment/approve"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "ids": [
        16
    ]
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/comment/approve',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'ids' => [
                16,
            ],
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "操作成功,1条记录通过审批",
    "data": ""
}
```

### HTTP Request
`POST api/admin/comment/approve`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `ids` | array |  required  | 评论id列表
        `ids.*` | integer |  required  | 评论id
    
<!-- END_ec795c34741d7b05102699cf4fc1150a -->

<!-- START_9a24e518beaa4249aef33adcd8333229 -->
## Reject submit comment

回绝评论,不予以显示该评论
Batch reject comments

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/comment/reject"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "ids": [
        6
    ]
}

fetch(url, {
    method: "DELETE",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->delete(
    'https://www.blog1997.com/api/admin/comment/reject',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'ids' => [
                6,
            ],
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "删除成功,1条记录被删除",
    "data": ""
}
```

### HTTP Request
`DELETE api/admin/comment/reject`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `ids` | array |  required  | 评论id列表
        `ids.*` | integer |  required  | 评论id
    
<!-- END_9a24e518beaa4249aef33adcd8333229 -->

<!-- START_14c0fdfd977651cd5ad410c61cad0b92 -->
## Get unverified comments

获取未审核的所有评论
Display a listing of the resource.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/comment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/comment',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "pagination": {
            "total": 1,
            "uri": "?",
            "currentPage": 1,
            "next": null,
            "previous": null,
            "first": 1,
            "last": 1,
            "pages": 1
        },
        "records": [
            {
                "id": 77,
                "content": "<p><img class=\"lazy\" data-src=\"http:\/\/img.baidu.com\/hi\/jx2\/j_0040.gif\" alt=\"j_0040.gif\" \/><\/p>",
                "able_id": 137,
                "able_type": "App\\Model\\Article",
                "user_id": 2,
                "created_at": "1618726336",
                "verified": "no",
                "thumbs": 0,
                "user": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                }
            }
        ]
    }
}
```

### HTTP Request
`GET api/admin/comment`


<!-- END_14c0fdfd977651cd5ad410c61cad0b92 -->

<!-- START_e795fade4d25e2473e7fd22cababfe99 -->
## Store newly create comment

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
添加评论

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/comment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "able_id": "reiciendis",
    "able_type": "ea",
    "content": "distinctio"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/comment',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'able_id' => 'reiciendis',
            'able_type' => 'ea',
            'content' => 'distinctio',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "评论成功",
    "data": {
        "user_id": 10,
        "able_id": 83,
        "able_type": "App\\Model\\Article",
        "content": "<p>test<img src=\"http:\/\/img.baidu.com\/hi\/jx2\/j_0001.gif\" alt=\"j_0001.gif\" \/><span class=\"mathquill-embedded-latex\" style=\"width:56.6875px;height:44px;\">_{n\\to\\infty}^{\\lim}<\/span><\/p>",
        "level": 1,
        "reply_to": 0,
        "reply_to_name": "Blog1997",
        "updated_at": "1598172027",
        "created_at": "1598172027",
        "id": 36,
        "thumbs": 0,
        "replies": [],
        "user": {
            "name": "blog1997:e2e"
        },
        "receiver": {
            "name": "receiver"
        }
    }
}
```

### HTTP Request
`POST api/comment`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `able_id` | mixed |  required  | 被评论记录的id
        `able_type` | string |  required  | 被评论记录的类型
        `content` | string |  required  | 评论的内容
    
<!-- END_e795fade4d25e2473e7fd22cababfe99 -->

<!-- START_e95d187a13bd8100da98069f12b91cc4 -->
## Destroy specific comment

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
删除评论
同时也会删除相关的回复内容

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/comment/corrupti"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->delete(
    'https://www.blog1997.com/api/comment/corrupti',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": [
        {
            "id": 75,
            "user_id": 2,
            "liked": 0,
            "content": "<p>3<\/p>",
            "commented": 0,
            "root_id": 71,
            "level": 2,
            "thumbs": 0,
            "user": {
                "id": 2,
                "name": "不高兴",
                "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
            },
            "receiver": {
                "id": null,
                "name": "该用户已注销",
                "avatar": ""
            }
        },
        {
            "id": 76,
            "user_id": 2,
            "liked": 0,
            "content": "<p>4<\/p>",
            "commented": 0,
            "root_id": 71,
            "level": 2,
            "thumbs": 0,
            "user": {
                "id": 2,
                "name": "不高兴",
                "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
            },
            "receiver": {
                "id": null,
                "name": "该用户已注销",
                "avatar": ""
            }
        }
    ]
}
```

### HTTP Request
`DELETE api/comment/{comment}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `comment` |  required  | 评论的id

<!-- END_e95d187a13bd8100da98069f12b91cc4 -->

<!-- START_5b5311d6597f9a2198d903b9cd0d363b -->
## Get reply of specific comment

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
获取评论的回复

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/comment/reply/1/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/comment/reply/1/1',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "删除评论成功",
    "data": {
        "rows": 1
    }
}
```

### HTTP Request
`GET api/comment/reply/{rootId}/{offset}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `$rootId` |  optional  | 评论的root_id
    `$offset` |  optional  | 记录的起始位置

<!-- END_5b5311d6597f9a2198d903b9cd0d363b -->

#Email config management


系统邮箱的配置
<!-- START_24c5506a30b4d1e83849896c0d78810a -->
## Get email config record

显示邮箱的配置,永远不会显示真实的授权码

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/email-config"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/email-config',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "id": 4,
        "driver": "smtp",
        "email_server": "smtp.163.com",
        "email_addr": "blog_1997@163.com",
        "port": 465,
        "encryption": "ssl",
        "sender": "Blog1997s",
        "password": "blog1997"
    }
}
```

### HTTP Request
`GET api/admin/email-config`


<!-- END_24c5506a30b4d1e83849896c0d78810a -->

<!-- START_41dcf0e2d4672f31c5e5e25f3de5a1b0 -->
## Store newly created records

添加邮箱配置

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/email-config"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email_server": "inventore",
    "port": 136.128595,
    "email_addr": "placeat",
    "encryption": "eligendi",
    "sender": "qui",
    "password": "impedit"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/email-config',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'email_server' => 'inventore',
            'port' => 136.128595,
            'email_addr' => 'placeat',
            'encryption' => 'eligendi',
            'sender' => 'qui',
            'password' => 'impedit',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "邮箱配置成功",
    "data": {
        "id": 1,
        "driver": "smtp",
        "email_server": "smtp.163.com",
        "email_addr": "blog_1997@163.com",
        "port": 465,
        "encryption": "ssl",
        "sender": "Blog1997",
        "password": "blog1997",
        "created_at": "1603533986",
        "updated_at": "1618732662"
    }
}
```

### HTTP Request
`POST api/admin/email-config`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email_server` | string |  required  | 邮箱服务器地址,例如:smtp.163.com
        `port` | number |  required  | 邮箱服务器端口,例如:465
        `email_addr` | string |  required  | 邮箱地址,例如:blog1997@163.com
        `encryption` | string |  required  | 加密方式,例如:ssl
        `sender` | string |  required  | 发件人名称,例如:blog1997
        `password` | string |  required  | 授权码,例如:S*********N
    
<!-- END_41dcf0e2d4672f31c5e5e25f3de5a1b0 -->

<!-- START_bd2b4ef955489f56ae57eea9ea76d691 -->
## Update a specified resource in storage.

修改邮箱配置

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/email-config/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email_server": "facilis",
    "port": 1025492.0690162537,
    "email_addr": "corporis",
    "encryption": "consectetur",
    "sender": "eos",
    "password": "commodi"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->put(
    'https://www.blog1997.com/api/admin/email-config/1',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'email_server' => 'facilis',
            'port' => 1025492.0690162537,
            'email_addr' => 'corporis',
            'encryption' => 'consectetur',
            'sender' => 'eos',
            'password' => 'commodi',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "邮箱配置修改成功",
    "data": {
        "id": 4,
        "driver": "smtp",
        "email_server": "smtp.163.com",
        "email_addr": "blog_1997@163.com",
        "port": 465,
        "encryption": "ssl",
        "sender": "Blog1997",
        "password": "blog1997",
        "created_at": "1603533986",
        "updated_at": "1618732662"
    }
}
```

### HTTP Request
`PUT api/admin/email-config/{email_config}`

`PATCH api/admin/email-config/{email_config}`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email_server` | string |  required  | 邮箱服务器地址,例如:smtp.163.com
        `port` | number |  required  | 邮箱服务器端口,例如:465
        `email_addr` | string |  required  | 邮箱地址,例如:blog1997@163.com
        `encryption` | string |  required  | 加密方式,例如:ssl
        `sender` | string |  required  | 发件人名称,例如:blog1997
        `password` | string |  required  | 授权码,例如:S*********N
    
<!-- END_bd2b4ef955489f56ae57eea9ea76d691 -->

#Friend link management


友情连接管理
Friend Link Management
<!-- START_235393713a5d1dfaa61b2b97b73e2dc0 -->
## Get friend link records

获取友链列表

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/friend-link"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/friend-link',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "pagination": {
            "total": 2,
            "uri": "?",
            "currentPage": 1,
            "next": null,
            "previous": null,
            "first": 1,
            "last": 1,
            "pages": 1
        },
        "records": [
            {
                "id": 1,
                "name": "blog1998s",
                "url": "https:\/\/www.blog1997.com",
                "editAble": 0
            },
            {
                "id": 2,
                "name": "Laravel9",
                "url": "https:\/\/www.laravel.com",
                "editAble": 0
            }
        ]
    }
}
```

### HTTP Request
`GET api/admin/friend-link`


<!-- END_235393713a5d1dfaa61b2b97b73e2dc0 -->

<!-- START_a46d0a55c3bcf939536feb794bcf0be2 -->
## Store newly created friend link records

新建友链数据

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/friend-link"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "est",
    "url": "dolorem"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/friend-link',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'name' => 'est',
            'url' => 'dolorem',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "友链添加成功",
    "data": {
        "name": "blog1997",
        "url": "https:\/\/www.blog1997.com",
        "updated_at": "1618733426",
        "created_at": "1618733426",
        "id": 12,
        "editAble": false
    }
}
```

### HTTP Request
`POST api/admin/friend-link`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | 友链名称,例如Blog1997
        `url` | string |  required  | 友链地址,例如https://www.blog1997.com
    
<!-- END_a46d0a55c3bcf939536feb794bcf0be2 -->

<!-- START_df6ad8b237c7610bf557d025032fef63 -->
## Update newly specified friend link record

更新友链

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/friend-link/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "sed",
    "url": "voluptatem"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->put(
    'https://www.blog1997.com/api/admin/friend-link/1',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'name' => 'sed',
            'url' => 'voluptatem',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "友链更新成功",
    "data": {
        "id": 12,
        "name": "Blog1997",
        "url": "https:\/\/www.blog1997.com",
        "created_at": "1618733426",
        "updated_at": "1618733534",
        "editAble": false
    }
}
```

### HTTP Request
`PUT api/admin/friend-link/{friend_link}`

`PATCH api/admin/friend-link/{friend_link}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `friendLink` |  optional  | 友链记录ID
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | 友链名称,例如Blog1997
        `url` | string |  required  | 友链地址,例如https://www.blog1997.com
    
<!-- END_df6ad8b237c7610bf557d025032fef63 -->

<!-- START_3403f12899cdc24ca6af318b76430cc0 -->
## Destroy newly specified friend link record

删除友链

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/friend-link/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->delete(
    'https://www.blog1997.com/api/admin/friend-link/1',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "友链删除成功",
    "data": ""
}
```

### HTTP Request
`DELETE api/admin/friend-link/{friend_link}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `friendLink` |  optional  | 友链ID

<!-- END_3403f12899cdc24ca6af318b76430cc0 -->

#Front index page


获取前台首页的相关数据
<!-- START_ae309226f6476a5c4acc7fb3419990bd -->
## index

获取首页相关信息

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "articles": [
            {
                "identity": "MTM3",
                "title": "测试框架Mocha使用",
                "is_origin": "yes",
                "user_id": 2,
                "is_markdown": "yes",
                "summary": "Mocha是一个功能丰富的javascript测试框架,运行在node.js和浏览器中，使异步测试变得简单有趣。为Javascript应用程序添加测试,不仅可以保证代码的质量，还可以获得一个像这样的小徽章 ![测试覆盖率报告](https:\/\/img.shields.io\/codecov\/c\/github\/youLookLikeDelicious\/animate?) 。除了Mocha，<a href=\"https:\/\/doc.ebichu.cc\/jest\/\" style=\"color: #00c6fb\" target=\"__blank\">Jest<\/a>、<a href=\"https:\/\/www.gitmemory.com\/avajs\/ava\" style=\"color: #00c6fb\" target=\"__blank\">Ava<\/a> 和 <a href=\"https:\/\/jasmine.github.io\/\" style=\"color: #00c6fb\" target=\"__blank\">Jasmine<\/a>等都是不错的选择。\n",
                "visited": 0,
                "gallery_id": 27,
                "commented": 0,
                "created_at": "1615702254",
                "updated_at": "1615783476",
                "author": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                },
                "gallery": {
                    "id": 27,
                    "url": "\/image\/gallery\/2020-12-09\/893.460028001615fd09025705076.85434726.jpg",
                    "thumbnail": null
                },
                "tags": [
                    {
                        "id": 2,
                        "name": "前端",
                        "pivot": {
                            "article_id": 137,
                            "tag_id": 2
                        }
                    }
                ]
            },
            {
                "identity": "MTM2",
                "title": "Webpack从入门到发布一个NPM包(一)",
                "is_origin": "yes",
                "user_id": 2,
                "is_markdown": "yes",
                "summary": "Webpack是Javascrpit程序的一个静态模型捆绑器。当webpack处理你的应用程序时，它再内部建立了一个依赖关系图，映射了项目中依赖的每个模块，并生成多个捆绑包。  \n在模块化设计中，开发者将程序拆散成离散的功能块，称之为模块。每个模块都有自己独立的作用域，方便验证、调试和测试。编写良好的模块，可以提供更好的抽象和封装，这样在应用程序中的每个模块都有一致的设计原则和明确的目的。\n",
                "visited": 0,
                "gallery_id": 26,
                "commented": 0,
                "created_at": "1615118758",
                "updated_at": "1615198588",
                "author": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                },
                "gallery": {
                    "id": 26,
                    "url": "\/image\/gallery\/2020-12-09\/73.0739020016085fd09025120bb4.45066014.jpg",
                    "thumbnail": null
                },
                "tags": [
                    {
                        "id": 2,
                        "name": "前端",
                        "pivot": {
                            "article_id": 136,
                            "tag_id": 2
                        }
                    }
                ]
            }
        ],
        "pages": 1,
        "p": 1,
        "articleNum": 2,
        "popArticles": [
            {
                "identity": "OTk=",
                "title": "贪婪算法-回溯",
                "visited": 2,
                "created_at": "1612844593"
            },
            {
                "identity": "MTAw",
                "title": "初识Laravel Facades和Contract",
                "visited": 0,
                "created_at": "1614765712"
            }
        ],
        "messageNum": 0
    }
}
```

### HTTP Request
`GET api`


<!-- END_ae309226f6476a5c4acc7fb3419990bd -->

<!-- START_0c98d923c2b54ee088f1d0951e2f897a -->
## getFriendLink
获取友链列表

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/friend-link"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/friend-link',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": [
        {
            "id": 1,
            "name": "blog1998s",
            "url": "https:\/\/www.blog1997.com"
        },
        {
            "id": 2,
            "name": "Laravel9",
            "url": "https:\/\/www.laravel.com"
        }
    ]
}
```

### HTTP Request
`GET api/friend-link`


<!-- END_0c98d923c2b54ee088f1d0951e2f897a -->

#Gallery management


管理相册
Gallery Management
<!-- START_65784f4a9406e72e47a00e5b74c5dffc -->
## Get galleries&#039; records

获取图片列表

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/gallery"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/gallery',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET api/admin/gallery`


<!-- END_65784f4a9406e72e47a00e5b74c5dffc -->

<!-- START_03742837291cf4dca4207c881e7d1308 -->
## Upload images

上传图片(可批量上传)
允许上传10M以内的图片,会在左下角生成blog1997文字水印
同时会备份一个webp格式的图片和一个等比缩放,宽度为240的缩略图(缩略图在前台懒加载的时候提升用户体验)

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/gallery"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "files": [
        "blanditiis"
    ]
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/gallery',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'files' => [
                'blanditiis',
            ],
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/admin/gallery`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `files` | array |  required  | 上传的文件列表
        `files.*` | image |  required  | 上传的图片
    
<!-- END_03742837291cf4dca4207c881e7d1308 -->

<!-- START_fb0dfb01a3d835db986c71874230d46d -->
## Remove updated image

删除上传图片
Delete gallery image

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/gallery/culpa"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->delete(
    'https://www.blog1997.com/api/admin/gallery/culpa',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "图片删除成功",
    "data": ""
}
```

### HTTP Request
`DELETE api/admin/gallery/{gallery}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `gallery` |  optional  | 相册图片的id

<!-- END_fb0dfb01a3d835db986c71874230d46d -->

#Home\ThumbUpController


点赞 文章|评论
<!-- START_d78300588d7df707679840c2bebd626e -->
## store
点赞操作

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/thumb-up"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": "quam",
    "category": "sit"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/thumb-up',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'id' => 'quam',
            'category' => 'sit',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": ""
}
```

### HTTP Request
`POST api/thumb-up`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | int|string |  required  | 文章|评论的ID
        `category` | string |  required  | 点赞的类型
    
<!-- END_d78300588d7df707679840c2bebd626e -->

#Leave message management


获取留言信息
<!-- START_77a9f5457b3fc2ceae64554081eb107f -->
## Get leave message

获取网站的留言

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/leave-message"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/leave-message',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "records": [
            {
                "id": 37,
                "content": "<p style=\"color:rgb(22,22,22);\"><img class=\"lazy\" data-src=\"http:\/\/img.baidu.com\/hi\/jx2\/j_0022.gif\" alt=\"j_0022.gif\" \/><\/p>",
                "user_id": 2,
                "level": 1,
                "reply_to": 0,
                "created_at": "1609852774",
                "liked": 0,
                "commented": 0,
                "thumbs": 0,
                "user": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                },
                "replies": []
            },
            {
                "id": 64,
                "content": "<p>&lt;script&gt;alert(1)&lt;\/script&gt;<\/p>",
                "user_id": 2,
                "level": 1,
                "reply_to": 0,
                "created_at": "1612943972",
                "liked": 0,
                "commented": 0,
                "thumbs": 0,
                "user": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                },
                "replies": []
            },
            {
                "id": 70,
                "content": "<p>**<\/p>",
                "user_id": 2,
                "level": 1,
                "reply_to": 0,
                "created_at": "1615886954",
                "liked": 0,
                "commented": 0,
                "thumbs": 0,
                "user": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                },
                "replies": []
            }
        ],
        "p": 1,
        "pages": 1,
        "commented": 0
    }
}
```

### HTTP Request
`GET api/leave-message`


<!-- END_77a9f5457b3fc2ceae64554081eb107f -->

#Log management


查询系统|用户日志
<!-- START_39addd5cbc4ad15ddac5c268ff9399ca -->
## Get logs

获取日志信息
Display a listing of the resource.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/log/minus"
);

let params = {
    "email": "id",
    "startDate": "iusto",
    "endDate": "aliquid",
    "p": "nemo",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/log/minus',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'query' => [
            'email'=> 'id',
            'startDate'=> 'iusto',
            'endDate'=> 'aliquid',
            'p'=> 'nemo',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "pagination": {
            "total": 18125,
            "uri": "?",
            "currentPage": 1,
            "next": 2,
            "previous": null,
            "first": 1,
            "last": 10,
            "pages": 907
        },
        "records": [
            {
                "id": 18164,
                "ip": "172.18.0.1",
                "port": "",
                "location": "局域网-",
                "result": "success",
                "time_consuming": 186,
                "user_id": 2,
                "created_at": 1618663018,
                "operate": "create",
                "message": "评论成功",
                "user": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                }
            },
            {
                "id": 18163,
                "ip": "172.18.0.1",
                "port": "",
                "location": "局域网-",
                "result": "success",
                "time_consuming": 184,
                "user_id": 2,
                "created_at": 1618663013,
                "operate": "create",
                "message": "评论成功",
                "user": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                }
            }
        ]
    }
}
```

### HTTP Request
`GET api/admin/log/{type?}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `type` |  optional  | 日志类型,例如:user,schedule
#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `email` |  optional  | 用户邮箱
    `startDate` |  optional  | 开始日期
    `endDate` |  optional  | 结束日期
    `p` |  optional  | 请求的页数

<!-- END_39addd5cbc4ad15ddac5c268ff9399ca -->

#Message management


管理后台的消息
包括通知信息、用户举报信息等相关的数据
<!-- START_f7ead4bb440315b92fcdc31b2fdd11be -->
## Get reported illegal records

获取举报信息

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/illegal-info"
);

let params = {
    "have_read": "earum",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/illegal-info',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'query' => [
            'have_read'=> 'earum',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "pagination": {
            "total": 3,
            "uri": "?",
            "currentPage": 1,
            "next": null,
            "previous": null,
            "first": 1,
            "last": 1,
            "pages": 1
        },
        "records": [
            {
                "id": 5,
                "sender": 3,
                "receiver": -1,
                "reported_id": 4,
                "type": "App\\Model\\Comment",
                "content": "<p><\/p>,段子或无意义的评论,",
                "created_at": "1609486779",
                "operate": "ignore",
                "have_read": "yes",
                "notificationable": {
                    "content": "该内容已被删除",
                    "thumbs": 0
                }
            },
            {
                "id": 7,
                "sender": 3,
                "receiver": -1,
                "reported_id": 81,
                "type": "App\\Model\\Article",
                "content": "https:\/\/www.blog1997.com\/article\/ODE=,抄袭或未授权转载",
                "created_at": "1609563156",
                "operate": "approve",
                "have_read": "yes"
            }
        ],
        "notHaveReadCount": 1,
        "total": 2,
        "haveRead": ""
    }
}
```

### HTTP Request
`GET api/admin/illegal-info`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `have_read` |  optional  | 消息是否已读,例如:yes,no

<!-- END_f7ead4bb440315b92fcdc31b2fdd11be -->

<!-- START_4f5bf162bbd402c98c356668a16c890e -->
## Approve illegal information

批准举报的信息,同时会删除对应的内容

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/illegal-info/approve/neque"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/illegal-info/approve/neque',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "审批成功",
    "data": ""
}
```

### HTTP Request
`POST api/admin/illegal-info/approve/{id}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  optional  | 举报记录的ID

<!-- END_4f5bf162bbd402c98c356668a16c890e -->

<!-- START_8bbe13e4e0703299025ffad48794998f -->
## Ignore reported illegal info

忽略举报的信息,会自动将该记录标记为已读

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/illegal-info/ignore/est"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/illegal-info/ignore/est',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "处理成功",
    "data": ""
}
```

### HTTP Request
`POST api/admin/illegal-info/ignore/{id}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  optional  | 举报记录的ID

<!-- END_8bbe13e4e0703299025ffad48794998f -->

<!-- START_91737be52f3fd616e9bc395261523c6c -->
## Get notification

获取通知的内容
Get Comment notification

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/notification"
);

let params = {
    "have_read": "saepe",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/notification',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'query' => [
            'have_read'=> 'saepe',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "pagination": {
            "total": 2,
            "uri": "?",
            "currentPage": 1,
            "next": null,
            "previous": null,
            "first": 1,
            "last": 1,
            "pages": 1
        },
        "records": [
            {
                "id": 4,
                "type": "App\\Model\\Comment",
                "content": "留下了一些足迹",
                "have_read": "no",
                "sender": 4,
                "reported_id": 4,
                "created_at": "1618752929",
                "updated_at": "1618752929",
                "notificationable": {
                    "id": 4,
                    "content": "<p>sdfadfa<br \/><\/p>",
                    "title": "",
                    "able_type": "Blog1997",
                    "able_id": 0,
                    "created_at": "1618752837",
                    "thumbs": 0,
                    "commentable": {
                        "comments": {
                            "pagination": []
                        }
                    }
                },
                "user": {
                    "id": 4,
                    "name": "chaos",
                    "avatar": "\/image\/avatar\/2020-11-06\/6352a96f3bbc772fdb84906077acb20b"
                }
            }
        ],
        "haveRead": "",
        "counts": {
            "total": 1,
            "have_read": 0
        }
    }
}
```

### HTTP Request
`GET api/admin/notification`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `have_read` |  optional  | 是否已读

<!-- END_91737be52f3fd616e9bc395261523c6c -->

<!-- START_7b1149884c8ae36361f551242c4186ec -->
## Get more comments about specific notification

获取通知相关的评论

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/notification/commentable-comments/aspernatur"
);

let params = {
    "p": "ratione",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/notification/commentable-comments/aspernatur',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'query' => [
            'p'=> 'ratione',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "pagination": {
            "total": 2,
            "uri": "?",
            "currentPage": 1,
            "next": null,
            "previous": null,
            "first": 1,
            "last": 1,
            "pages": 1
        },
        "records": [
            {
                "id": 3,
                "content": "<p>12312<br \/><\/p>",
                "created_at": "1618752806",
                "user_id": 2,
                "root_id": 0,
                "thumbs": 0,
                "user": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                }
            },
            {
                "id": 4,
                "content": "<p>sdfadfa<br \/><\/p>",
                "created_at": "1618752837",
                "user_id": 2,
                "root_id": 0,
                "thumbs": 0,
                "user": {
                    "id": 2,
                    "name": "不高兴",
                    "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg"
                }
            }
        ]
    }
}
```

### HTTP Request
`GET api/admin/notification/commentable-comments/{id}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  optional  | 通知的ID
#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `p` |  optional  | 请求的页数

<!-- END_7b1149884c8ae36361f551242c4186ec -->

#Report illegal information management


举报 文章|评论
<!-- START_add8276f8064117c9daa0cdb10087f82 -->
## Report article or comment

举报违法信息

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/report-illegal-info"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "sender": 19,
    "content": "et",
    "type": 17,
    "reported_id": "consequuntur"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/report-illegal-info',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'sender' => 19,
            'content' => 'et',
            'type' => 17,
            'reported_id' => 'consequuntur',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "举报已提交,感谢您的配合",
    "data": ""
}
```

### HTTP Request
`POST api/report-illegal-info`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `sender` | integer |  required  | 举报者ID
        `content` | string |  optional  | nullable 举报的内容
        `type` | integer |  required  | 举报内容的类型:1文章, 2评论
        `reported_id` | int|string |  required  | 被举报内容的ID
    
<!-- END_add8276f8064117c9daa0cdb10087f82 -->

#Retrieve Image


获取本站的图片
<!-- START_bc868e41b634596e579331ee9dd95385 -->
## 获取上传的图片

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/image/nihil/placeat/1/ad"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/image/nihil/placeat/1/ad',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`GET image/{type}/{dir}/{name}/{isWebp?}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `type` |  optional  | string 图片类型,例如avatar,article,gallery
    `dir` |  optional  | string 日期信息,例如2021-01-04
    `isWebp` |  optional  | 是否是webp,默认是webp格式

<!-- END_bc868e41b634596e579331ee9dd95385 -->

#Role Base Access Control Management


基于角色的权限控制
<!-- START_3d7ad7530378d9df09ff8c865ef8c2de -->
## Display a listing of the resource.

获取所有的角色列表

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/role"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/role',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "pagination": {
            "total": 3,
            "uri": "?",
            "currentPage": 1,
            "next": null,
            "previous": null,
            "first": 1,
            "last": 1,
            "pages": 1
        },
        "records": [
            {
                "id": 1,
                "name": "Master",
                "remark": "Supper admin",
                "authorities": [
                    {
                        "id": 38,
                        "name": "上传图片",
                        "pivot": {
                            "role_id": 1,
                            "auth_id": 38
                        }
                    },
                    {
                        "id": 13,
                        "name": "专题管理",
                        "pivot": {
                            "role_id": 1,
                            "auth_id": 13
                        }
                    }
                ]
            },
            {
                "id": 2,
                "name": "注册用户",
                "remark": "使用第三方账号登陆的用户",
                "authorities": []
            }
        ]
    }
}
```

### HTTP Request
`GET api/admin/role`


<!-- END_3d7ad7530378d9df09ff8c865ef8c2de -->

<!-- START_238eadf696738fbd1185c39619d7ca3f -->
## Store a newly created role in storage.

新建角色

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/role"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "consectetur",
    "remark": "illo",
    "authorities": [
        4
    ]
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/role',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'name' => 'consectetur',
            'remark' => 'illo',
            'authorities' => [
                4,
            ],
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "角色添加成功",
    "data": {
        "name": "11",
        "remark": "",
        "updated_at": "1618836138",
        "created_at": "1618836138",
        "id": 6,
        "authorities": [
            {
                "id": 1,
                "name": "博客管理",
                "pivot": {
                    "role_id": 6,
                    "auth_id": 1
                }
            },
            {
                "id": 16,
                "name": "修改专题",
                "pivot": {
                    "role_id": 6,
                    "auth_id": 16
                }
            }
        ]
    }
}
```

### HTTP Request
`POST api/admin/role`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | 角色名称
        `remark` | string |  optional  | 备注
        `authorities` | array |  optional  | 权限列表
        `authorities.*` | integer |  required  | 权限id
    
<!-- END_238eadf696738fbd1185c39619d7ca3f -->

<!-- START_a9af831a5b3c8efee706f76b623d7beb -->
## Update a specified role in storage.

更新角色

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/role/qui"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "ullam",
    "remark": "expedita",
    "authorities": [
        15
    ]
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->put(
    'https://www.blog1997.com/api/admin/role/qui',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'name' => 'ullam',
            'remark' => 'expedita',
            'authorities' => [
                15,
            ],
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "角色更新成功",
    "data": {
        "id": 5,
        "name": "author",
        "remark": "备注",
        "created_at": "1618835002",
        "updated_at": "1618835952",
        "authorities": [
            {
                "id": 2,
                "name": "文章管理",
                "pivot": {
                    "role_id": 5,
                    "auth_id": 2
                }
            }
        ]
    }
}
```

### HTTP Request
`PUT api/admin/role/{role}`

`PATCH api/admin/role/{role}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `role` |  optional  | 角色id
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | 角色名称
        `remark` | string |  optional  | 备注
        `authorities` | array |  optional  | 权限列表
        `authorities.*` | integer |  required  | 权限id
    
<!-- END_a9af831a5b3c8efee706f76b623d7beb -->

<!-- START_7294a4bde1eb22d1aea003ab95fced5d -->
## Remove the specified role from storage.

移除角色,同时也会移除和权限对应的多对多关系

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/role/facilis"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->delete(
    'https://www.blog1997.com/api/admin/role/facilis',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "角色删除成功",
    "data": ""
}
```

### HTTP Request
`DELETE api/admin/role/{role}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `role` |  optional  | 角色ID

<!-- END_7294a4bde1eb22d1aea003ab95fced5d -->

#Role base access control management


RBAC-权限管理
Auth Management
<!-- START_3d314a6f1ca59723796d8975c1082c3b -->
## Display auth records

显示所有的权限

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/auth"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/auth',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "pagination": {
            "total": 77,
            "uri": "?",
            "currentPage": 1,
            "next": null,
            "previous": null,
            "first": 1,
            "last": 1,
            "pages": 1
        },
        "records": [
            {
                "id": 1,
                "name": "博客管理",
                "parent_id": 0,
                "auth_path": "1_",
                "route_name": "blog.manage"
            },
            {
                "id": 13,
                "name": "专题管理",
                "parent_id": 1,
                "auth_path": "1_13_",
                "route_name": ""
            },
            {
                "id": 14,
                "name": "查看专题",
                "parent_id": 13,
                "auth_path": "1_13_14_",
                "route_name": "topic.index"
            },
            {
                "id": 15,
                "name": "创建专题",
                "parent_id": 13,
                "auth_path": "1_13_15_",
                "route_name": "topic.store"
            },
            {
                "id": 16,
                "name": "修改专题",
                "parent_id": 13,
                "auth_path": "1_13_16_",
                "route_name": "topic.update"
            },
            {
                "id": 17,
                "name": "删除专题",
                "parent_id": 13,
                "auth_path": "1_13_17_",
                "route_name": "topic.destroy"
            },
            {
                "id": 2,
                "name": "文章管理",
                "parent_id": 1,
                "auth_path": "1_2_",
                "route_name": ""
            },
            {
                "id": 6,
                "name": "添加文章",
                "parent_id": 2,
                "auth_path": "1_2_6_",
                "route_name": "article.store"
            },
            {
                "id": 7,
                "name": "修改文章",
                "parent_id": 2,
                "auth_path": "1_2_7_",
                "route_name": "article.update"
            },
            {
                "id": 8,
                "name": "删除文章",
                "parent_id": 2,
                "auth_path": "1_2_8_",
                "route_name": "article.destroy"
            },
            {
                "id": 9,
                "name": "查看列表",
                "parent_id": 2,
                "auth_path": "1_2_9_",
                "route_name": "article.index"
            },
            {
                "id": 4,
                "name": "标签管理",
                "parent_id": 1,
                "auth_path": "1_4_",
                "route_name": ""
            },
            {
                "id": 10,
                "name": "查看标签",
                "parent_id": 4,
                "auth_path": "1_4_10_",
                "route_name": "tag.index"
            },
            {
                "id": 11,
                "name": "更新标签",
                "parent_id": 4,
                "auth_path": "1_4_11_",
                "route_name": "tag.update"
            },
            {
                "id": 12,
                "name": "删除标签",
                "parent_id": 4,
                "auth_path": "1_4_12_",
                "route_name": "tag.destroy"
            },
            {
                "id": 5,
                "name": "添加标签",
                "parent_id": 4,
                "auth_path": "1_4_5_",
                "route_name": "tag.store"
            },
            {
                "id": 18,
                "name": "工作台",
                "parent_id": 0,
                "auth_path": "18_",
                "route_name": "workbench"
            },
            {
                "id": 19,
                "name": "仪表盘",
                "parent_id": 18,
                "auth_path": "18_19_",
                "route_name": "admin.dashboard"
            },
            {
                "id": 20,
                "name": "授权管理",
                "parent_id": 0,
                "auth_path": "20_",
                "route_name": "authorize.manage"
            },
            {
                "id": 21,
                "name": "权限管理",
                "parent_id": 20,
                "auth_path": "20_21_",
                "route_name": "auth.manage"
            },
            {
                "id": 22,
                "name": "查看权限",
                "parent_id": 21,
                "auth_path": "20_21_22_",
                "route_name": "auth.index"
            },
            {
                "id": 23,
                "name": "添加权限",
                "parent_id": 21,
                "auth_path": "20_21_23_",
                "route_name": "auth.store"
            },
            {
                "id": 24,
                "name": "更改权限",
                "parent_id": 21,
                "auth_path": "20_21_24_",
                "route_name": "auth.update"
            },
            {
                "id": 25,
                "name": "删除权限",
                "parent_id": 21,
                "auth_path": "20_21_25_",
                "route_name": "auth.destroy"
            },
            {
                "id": 26,
                "name": "角色管理",
                "parent_id": 20,
                "auth_path": "20_26_",
                "route_name": ""
            },
            {
                "id": 27,
                "name": "查看角色",
                "parent_id": 26,
                "auth_path": "20_26_27_",
                "route_name": "role.index"
            },
            {
                "id": 28,
                "name": "添加角色",
                "parent_id": 26,
                "auth_path": "20_26_28_",
                "route_name": "role.store"
            },
            {
                "id": 29,
                "name": "修改角色",
                "parent_id": 26,
                "auth_path": "20_26_29_",
                "route_name": "role.update"
            },
            {
                "id": 30,
                "name": "删除角色",
                "parent_id": 26,
                "auth_path": "20_26_30_",
                "route_name": "role.destroy"
            },
            {
                "id": 31,
                "name": "管理员管理",
                "parent_id": 20,
                "auth_path": "20_31_",
                "route_name": ""
            },
            {
                "id": 32,
                "name": "添加管理员",
                "parent_id": 31,
                "auth_path": "20_31_32_",
                "route_name": "manager.create"
            },
            {
                "id": 33,
                "name": "查看管理员",
                "parent_id": 31,
                "auth_path": "20_31_33_",
                "route_name": "manager.index"
            },
            {
                "id": 34,
                "name": "删除管理员",
                "parent_id": 31,
                "auth_path": "20_31_34_",
                "route_name": "manager.destroy"
            },
            {
                "id": 35,
                "name": "修改管理员",
                "parent_id": 31,
                "auth_path": "20_31_35_",
                "route_name": "manager.uddate"
            },
            {
                "id": 82,
                "name": "管理员界面获取用户信息",
                "parent_id": 31,
                "auth_path": "20_31_82_",
                "route_name": "manager.get.user"
            },
            {
                "id": 36,
                "name": "画廊管理",
                "parent_id": 0,
                "auth_path": "36_",
                "route_name": ""
            },
            {
                "id": 37,
                "name": "查看图片",
                "parent_id": 36,
                "auth_path": "36_37_",
                "route_name": "gallery.index"
            },
            {
                "id": 38,
                "name": "上传图片",
                "parent_id": 36,
                "auth_path": "36_38_",
                "route_name": "gallery.store"
            },
            {
                "id": 39,
                "name": "友链管理",
                "parent_id": 0,
                "auth_path": "39_",
                "route_name": ""
            },
            {
                "id": 40,
                "name": "添加友链",
                "parent_id": 39,
                "auth_path": "39_40_",
                "route_name": "friend-link.store"
            },
            {
                "id": 41,
                "name": "查看友链",
                "parent_id": 39,
                "auth_path": "39_41_",
                "route_name": "friend-link.index"
            },
            {
                "id": 42,
                "name": "修改友链",
                "parent_id": 39,
                "auth_path": "39_42_",
                "route_name": "friend-link.update"
            },
            {
                "id": 43,
                "name": "删除友链",
                "parent_id": 39,
                "auth_path": "39_43_",
                "route_name": "friend-link.destroy"
            },
            {
                "id": 44,
                "name": "敏感词汇管理",
                "parent_id": 0,
                "auth_path": "44_",
                "route_name": "sensitive-word.manage"
            },
            {
                "id": 45,
                "name": "分类管理",
                "parent_id": 44,
                "auth_path": "44_45_",
                "route_name": ""
            },
            {
                "id": 46,
                "name": "查看分类",
                "parent_id": 45,
                "auth_path": "44_45_46_",
                "route_name": "sensitive-word-category.index"
            },
            {
                "id": 47,
                "name": "删除分类",
                "parent_id": 45,
                "auth_path": "44_45_47_",
                "route_name": "sensitive-word-category.destroy"
            },
            {
                "id": 48,
                "name": "添加分类",
                "parent_id": 45,
                "auth_path": "44_45_48_",
                "route_name": "sensitive-word-category.store"
            },
            {
                "id": 49,
                "name": "修改分类",
                "parent_id": 45,
                "auth_path": "44_45_49_",
                "route_name": "sensitive-word-category.update"
            },
            {
                "id": 50,
                "name": "词汇管理",
                "parent_id": 44,
                "auth_path": "44_50_",
                "route_name": ""
            },
            {
                "id": 51,
                "name": "添加词汇",
                "parent_id": 50,
                "auth_path": "44_50_51_",
                "route_name": "sensitive-word.store"
            },
            {
                "id": 52,
                "name": "查看词汇",
                "parent_id": 50,
                "auth_path": "44_50_52_",
                "route_name": "sensitive-word.index"
            },
            {
                "id": 53,
                "name": "修改词汇",
                "parent_id": 50,
                "auth_path": "44_50_53_",
                "route_name": "sensitive-word.update"
            },
            {
                "id": 54,
                "name": "删除词汇",
                "parent_id": 50,
                "auth_path": "44_50_54_",
                "route_name": "sensitive-word.destroy"
            },
            {
                "id": 56,
                "name": "系统管理",
                "parent_id": 0,
                "auth_path": "56_",
                "route_name": "system.manage"
            },
            {
                "id": 68,
                "name": "系统日志",
                "parent_id": 56,
                "auth_path": "56_68_",
                "route_name": ""
            },
            {
                "id": 70,
                "name": "查看日志",
                "parent_id": 68,
                "auth_path": "56_68_70_",
                "route_name": "system.log"
            },
            {
                "id": 69,
                "name": "系统设置",
                "parent_id": 56,
                "auth_path": "56_69_",
                "route_name": ""
            },
            {
                "id": 74,
                "name": "查看系统设置",
                "parent_id": 69,
                "auth_path": "56_69_74_",
                "route_name": "system-setting.index"
            },
            {
                "id": 75,
                "name": "更新系统设置",
                "parent_id": 69,
                "auth_path": "56_69_75_",
                "route_name": "system-setting.update"
            },
            {
                "id": 59,
                "name": "消息管理",
                "parent_id": 0,
                "auth_path": "59_",
                "route_name": ""
            },
            {
                "id": 60,
                "name": "举报信息管理",
                "parent_id": 59,
                "auth_path": "59_60_",
                "route_name": ""
            },
            {
                "id": 61,
                "name": "查看举报信息",
                "parent_id": 60,
                "auth_path": "59_60_61_",
                "route_name": "illegal-info.index"
            },
            {
                "id": 62,
                "name": "删除被举报的内容",
                "parent_id": 60,
                "auth_path": "59_60_62_",
                "route_name": "illegal-info.approve"
            },
            {
                "id": 63,
                "name": "忽略举报的内容",
                "parent_id": 60,
                "auth_path": "59_60_63_",
                "route_name": "illegal-info.ignore"
            },
            {
                "id": 64,
                "name": "待审核评论管理",
                "parent_id": 59,
                "auth_path": "59_64_",
                "route_name": ""
            },
            {
                "id": 65,
                "name": "批准评论",
                "parent_id": 64,
                "auth_path": "59_64_65_",
                "route_name": "comment.approve"
            },
            {
                "id": 66,
                "name": "查看待审核评论",
                "parent_id": 64,
                "auth_path": "59_64_66_",
                "route_name": "comment.index"
            },
            {
                "id": 67,
                "name": "驳回评论",
                "parent_id": 64,
                "auth_path": "59_64_67_",
                "route_name": "comment.reject"
            },
            {
                "id": 73,
                "name": "通知",
                "parent_id": 59,
                "auth_path": "59_73_",
                "route_name": "notification.index"
            },
            {
                "id": 76,
                "name": "账号设置",
                "parent_id": 0,
                "auth_path": "76_",
                "route_name": ""
            },
            {
                "id": 77,
                "name": "查看账号信息",
                "parent_id": 76,
                "auth_path": "76_77_",
                "route_name": "user.profile"
            },
            {
                "id": 78,
                "name": "绑定社交账号",
                "parent_id": 76,
                "auth_path": "76_78_",
                "route_name": "user.bind"
            },
            {
                "id": 79,
                "name": "取消绑定",
                "parent_id": 76,
                "auth_path": "76_79_",
                "route_name": "user.unbind"
            },
            {
                "id": 80,
                "name": "重新绑定社交账号",
                "parent_id": 76,
                "auth_path": "76_80_",
                "route_name": "user.rebind"
            },
            {
                "id": 81,
                "name": "修改基本信息",
                "parent_id": 76,
                "auth_path": "76_81_",
                "route_name": "user.update"
            },
            {
                "id": 83,
                "name": "访问后台",
                "parent_id": 0,
                "auth_path": "83_",
                "route_name": "admin.index"
            }
        ],
        "topAuth": [
            {
                "id": "",
                "name": "--所有权限--"
            },
            {
                "id": 1,
                "name": "博客管理"
            },
            {
                "id": 18,
                "name": "工作台"
            },
            {
                "id": 20,
                "name": "授权管理"
            },
            {
                "id": 36,
                "name": "画廊管理"
            },
            {
                "id": 39,
                "name": "友链管理"
            },
            {
                "id": 44,
                "name": "敏感词汇管理"
            },
            {
                "id": 56,
                "name": "系统管理"
            },
            {
                "id": 59,
                "name": "消息管理"
            },
            {
                "id": 76,
                "name": "账号设置"
            },
            {
                "id": 83,
                "name": "访问后台"
            }
        ]
    }
}
```

### HTTP Request
`GET api/admin/auth`


<!-- END_3d314a6f1ca59723796d8975c1082c3b -->

<!-- START_b78765ccf4ca0741de87e122d226a67e -->
## Get auth records when create new auth

获取创建权限所需的数据
Show the form data for creating a new resource.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/auth/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/auth/create',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": [
        {
            "id": 0,
            "name": "--顶级权限--"
        },
        {
            "id": 1,
            "name": "博客管理",
            "auth_path": "1_",
            "parent_id": 0
        },
        {
            "id": 13,
            "name": "专题管理",
            "auth_path": "1_13_",
            "parent_id": 1
        },
        {
            "id": 14,
            "name": "查看专题",
            "auth_path": "1_13_14_",
            "parent_id": 13
        },
        {
            "id": 15,
            "name": "创建专题",
            "auth_path": "1_13_15_",
            "parent_id": 13
        },
        {
            "id": 16,
            "name": "修改专题",
            "auth_path": "1_13_16_",
            "parent_id": 13
        },
        {
            "id": 17,
            "name": "删除专题",
            "auth_path": "1_13_17_",
            "parent_id": 13
        },
        {
            "id": 2,
            "name": "文章管理",
            "auth_path": "1_2_",
            "parent_id": 1
        },
        {
            "id": 6,
            "name": "添加文章",
            "auth_path": "1_2_6_",
            "parent_id": 2
        },
        {
            "id": 7,
            "name": "修改文章",
            "auth_path": "1_2_7_",
            "parent_id": 2
        },
        {
            "id": 8,
            "name": "删除文章",
            "auth_path": "1_2_8_",
            "parent_id": 2
        },
        {
            "id": 9,
            "name": "查看列表",
            "auth_path": "1_2_9_",
            "parent_id": 2
        },
        {
            "id": 4,
            "name": "标签管理",
            "auth_path": "1_4_",
            "parent_id": 1
        },
        {
            "id": 10,
            "name": "查看标签",
            "auth_path": "1_4_10_",
            "parent_id": 4
        },
        {
            "id": 11,
            "name": "更新标签",
            "auth_path": "1_4_11_",
            "parent_id": 4
        },
        {
            "id": 12,
            "name": "删除标签",
            "auth_path": "1_4_12_",
            "parent_id": 4
        },
        {
            "id": 5,
            "name": "添加标签",
            "auth_path": "1_4_5_",
            "parent_id": 4
        },
        {
            "id": 18,
            "name": "工作台",
            "auth_path": "18_",
            "parent_id": 0
        },
        {
            "id": 19,
            "name": "仪表盘",
            "auth_path": "18_19_",
            "parent_id": 18
        },
        {
            "id": 20,
            "name": "授权管理",
            "auth_path": "20_",
            "parent_id": 0
        },
        {
            "id": 21,
            "name": "权限管理",
            "auth_path": "20_21_",
            "parent_id": 20
        },
        {
            "id": 22,
            "name": "查看权限",
            "auth_path": "20_21_22_",
            "parent_id": 21
        },
        {
            "id": 23,
            "name": "添加权限",
            "auth_path": "20_21_23_",
            "parent_id": 21
        },
        {
            "id": 24,
            "name": "更改权限",
            "auth_path": "20_21_24_",
            "parent_id": 21
        },
        {
            "id": 25,
            "name": "删除权限",
            "auth_path": "20_21_25_",
            "parent_id": 21
        },
        {
            "id": 26,
            "name": "角色管理",
            "auth_path": "20_26_",
            "parent_id": 20
        },
        {
            "id": 27,
            "name": "查看角色",
            "auth_path": "20_26_27_",
            "parent_id": 26
        },
        {
            "id": 28,
            "name": "添加角色",
            "auth_path": "20_26_28_",
            "parent_id": 26
        },
        {
            "id": 29,
            "name": "修改角色",
            "auth_path": "20_26_29_",
            "parent_id": 26
        },
        {
            "id": 30,
            "name": "删除角色",
            "auth_path": "20_26_30_",
            "parent_id": 26
        },
        {
            "id": 31,
            "name": "管理员管理",
            "auth_path": "20_31_",
            "parent_id": 20
        },
        {
            "id": 32,
            "name": "添加管理员",
            "auth_path": "20_31_32_",
            "parent_id": 31
        },
        {
            "id": 33,
            "name": "查看管理员",
            "auth_path": "20_31_33_",
            "parent_id": 31
        },
        {
            "id": 34,
            "name": "删除管理员",
            "auth_path": "20_31_34_",
            "parent_id": 31
        },
        {
            "id": 35,
            "name": "修改管理员",
            "auth_path": "20_31_35_",
            "parent_id": 31
        },
        {
            "id": 82,
            "name": "管理员界面获取用户信息",
            "auth_path": "20_31_82_",
            "parent_id": 31
        },
        {
            "id": 36,
            "name": "画廊管理",
            "auth_path": "36_",
            "parent_id": 0
        },
        {
            "id": 37,
            "name": "查看图片",
            "auth_path": "36_37_",
            "parent_id": 36
        },
        {
            "id": 38,
            "name": "上传图片",
            "auth_path": "36_38_",
            "parent_id": 36
        },
        {
            "id": 39,
            "name": "友链管理",
            "auth_path": "39_",
            "parent_id": 0
        },
        {
            "id": 40,
            "name": "添加友链",
            "auth_path": "39_40_",
            "parent_id": 39
        },
        {
            "id": 41,
            "name": "查看友链",
            "auth_path": "39_41_",
            "parent_id": 39
        },
        {
            "id": 42,
            "name": "修改友链",
            "auth_path": "39_42_",
            "parent_id": 39
        },
        {
            "id": 43,
            "name": "删除友链",
            "auth_path": "39_43_",
            "parent_id": 39
        },
        {
            "id": 44,
            "name": "敏感词汇管理",
            "auth_path": "44_",
            "parent_id": 0
        },
        {
            "id": 45,
            "name": "分类管理",
            "auth_path": "44_45_",
            "parent_id": 44
        },
        {
            "id": 46,
            "name": "查看分类",
            "auth_path": "44_45_46_",
            "parent_id": 45
        },
        {
            "id": 47,
            "name": "删除分类",
            "auth_path": "44_45_47_",
            "parent_id": 45
        },
        {
            "id": 48,
            "name": "添加分类",
            "auth_path": "44_45_48_",
            "parent_id": 45
        },
        {
            "id": 49,
            "name": "修改分类",
            "auth_path": "44_45_49_",
            "parent_id": 45
        },
        {
            "id": 50,
            "name": "词汇管理",
            "auth_path": "44_50_",
            "parent_id": 44
        },
        {
            "id": 51,
            "name": "添加词汇",
            "auth_path": "44_50_51_",
            "parent_id": 50
        },
        {
            "id": 52,
            "name": "查看词汇",
            "auth_path": "44_50_52_",
            "parent_id": 50
        },
        {
            "id": 53,
            "name": "修改词汇",
            "auth_path": "44_50_53_",
            "parent_id": 50
        },
        {
            "id": 54,
            "name": "删除词汇",
            "auth_path": "44_50_54_",
            "parent_id": 50
        },
        {
            "id": 56,
            "name": "系统管理",
            "auth_path": "56_",
            "parent_id": 0
        },
        {
            "id": 68,
            "name": "系统日志",
            "auth_path": "56_68_",
            "parent_id": 56
        },
        {
            "id": 70,
            "name": "查看日志",
            "auth_path": "56_68_70_",
            "parent_id": 68
        },
        {
            "id": 69,
            "name": "系统设置",
            "auth_path": "56_69_",
            "parent_id": 56
        },
        {
            "id": 74,
            "name": "查看系统设置",
            "auth_path": "56_69_74_",
            "parent_id": 69
        },
        {
            "id": 75,
            "name": "更新系统设置",
            "auth_path": "56_69_75_",
            "parent_id": 69
        },
        {
            "id": 59,
            "name": "消息管理",
            "auth_path": "59_",
            "parent_id": 0
        },
        {
            "id": 60,
            "name": "举报信息管理",
            "auth_path": "59_60_",
            "parent_id": 59
        },
        {
            "id": 61,
            "name": "查看举报信息",
            "auth_path": "59_60_61_",
            "parent_id": 60
        },
        {
            "id": 62,
            "name": "删除被举报的内容",
            "auth_path": "59_60_62_",
            "parent_id": 60
        },
        {
            "id": 63,
            "name": "忽略举报的内容",
            "auth_path": "59_60_63_",
            "parent_id": 60
        },
        {
            "id": 64,
            "name": "待审核评论管理",
            "auth_path": "59_64_",
            "parent_id": 59
        },
        {
            "id": 65,
            "name": "批准评论",
            "auth_path": "59_64_65_",
            "parent_id": 64
        },
        {
            "id": 66,
            "name": "查看待审核评论",
            "auth_path": "59_64_66_",
            "parent_id": 64
        },
        {
            "id": 67,
            "name": "驳回评论",
            "auth_path": "59_64_67_",
            "parent_id": 64
        },
        {
            "id": 73,
            "name": "通知",
            "auth_path": "59_73_",
            "parent_id": 59
        },
        {
            "id": 76,
            "name": "账号设置",
            "auth_path": "76_",
            "parent_id": 0
        },
        {
            "id": 77,
            "name": "查看账号信息",
            "auth_path": "76_77_",
            "parent_id": 76
        },
        {
            "id": 78,
            "name": "绑定社交账号",
            "auth_path": "76_78_",
            "parent_id": 76
        },
        {
            "id": 79,
            "name": "取消绑定",
            "auth_path": "76_79_",
            "parent_id": 76
        },
        {
            "id": 80,
            "name": "重新绑定社交账号",
            "auth_path": "76_80_",
            "parent_id": 76
        },
        {
            "id": 81,
            "name": "修改基本信息",
            "auth_path": "76_81_",
            "parent_id": 76
        },
        {
            "id": 83,
            "name": "访问后台",
            "auth_path": "83_",
            "parent_id": 0
        }
    ]
}
```

### HTTP Request
`GET api/admin/auth/create`


<!-- END_b78765ccf4ca0741de87e122d226a67e -->

<!-- START_551479c1b40d73a170b264c537a7c01a -->
## Store newly created auth

新建一个权限记录

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/auth"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "dolore",
    "parent_id": 10,
    "route_name": "aut"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/auth',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'name' => 'dolore',
            'parent_id' => 10,
            'route_name' => 'aut',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "权限添加成功",
    "data": {
        "name": "test",
        "parent_id": 0,
        "route_name": "",
        "updated_at": "1618725637",
        "created_at": "1618725637",
        "id": 84,
        "auth_path": "84_"
    }
}
```

### HTTP Request
`POST api/admin/auth`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | 权限名称
        `parent_id` | integer |  required  | 父权限id,该值为0的时候,表示该权限是顶级权限
        `route_name` | string |  optional  | 权限对应的路由名称
    
<!-- END_551479c1b40d73a170b264c537a7c01a -->

<!-- START_c9edcee45bd0720470764100a05cc104 -->
## Destroy the specific auth

删除权限,同时角色对应的权限也会移除

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/auth/quibusdam"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->delete(
    'https://www.blog1997.com/api/admin/auth/quibusdam',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "权限删除成功",
    "data": ""
}
```

### HTTP Request
`DELETE api/admin/auth/{auth}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `auth` |  optional  | 权限id

<!-- END_c9edcee45bd0720470764100a05cc104 -->

<!-- START_1b8c5d579395f214b6f4d534639d58b7 -->
## Get user records when assign roles to user

分配权限的时候,获取用户信息
Get user and roles information after enter user email

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/manager/user/qui"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/manager/user/qui',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "id": 2,
        "name": "不高兴",
        "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg",
        "roles": [
            {
                "id": 1,
                "name": "Master",
                "pivot": {
                    "user_id": 2,
                    "role_id": 1,
                    "created_at": "2020-12-01 16:19:35",
                    "updated_at": "2020-12-01 16:19:35"
                }
            }
        ]
    }
}
```

### HTTP Request
`GET api/admin/manager/user/{email}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `email` |  optional  | 用户邮箱

<!-- END_1b8c5d579395f214b6f4d534639d58b7 -->

<!-- START_24a84fe1046135e1f12f4779c75c377b -->
## Get list of manager

获取管理员列表
赋予角色的用户被认为是管理员
Display a listing of the resource.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/manager"
);

let params = {
    "email": "quisquam",
    "role_id": "autem",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/manager',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'query' => [
            'email'=> 'quisquam',
            'role_id'=> 'autem',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "pagination": {
            "total": 1,
            "uri": "?",
            "currentPage": 1,
            "next": null,
            "previous": null,
            "first": 1,
            "last": 1,
            "pages": 1
        },
        "records": [
            {
                "id": 2,
                "name": "不高兴",
                "avatar": "\/image\/avatar\/2021-01-04\/792.071792001615ff318151187d1.11539176.jpg",
                "email": "1059366817@qq.com",
                "deleted_at": 0,
                "created_at": "1602845263",
                "email_verified_at": "2021-01-07 10:23:06",
                "editAble": 1,
                "roles": [
                    {
                        "id": 1,
                        "name": "Master",
                        "pivot": {
                            "user_id": 2,
                            "role_id": 1,
                            "created_at": "2020-12-01 16:19:35",
                            "updated_at": "2020-12-01 16:19:35"
                        }
                    }
                ]
            }
        ],
        "roles": [
            {
                "id": 0,
                "name": "--所有角色--"
            },
            {
                "id": 3,
                "name": "Author"
            },
            {
                "id": 1,
                "name": "Master"
            },
            {
                "id": 2,
                "name": "注册用户"
            }
        ]
    }
}
```

### HTTP Request
`GET api/admin/manager`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `email` |  optional  | 管理员邮箱
    `role_id` |  optional  | 角色ID

<!-- END_24a84fe1046135e1f12f4779c75c377b -->

<!-- START_f76fd2ff9496e42e78c57290e89db143 -->
## Get user info when assign roles

为角色赋予管理员时,获取所有的角色列表
Show the form for creating a new resource.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/manager/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/manager/create',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": [
        {
            "id": 3,
            "name": "Author"
        },
        {
            "id": 1,
            "name": "Master"
        },
        {
            "id": 2,
            "name": "注册用户"
        }
    ]
}
```

### HTTP Request
`GET api/admin/manager/create`


<!-- END_f76fd2ff9496e42e78c57290e89db143 -->

<!-- START_724f6ec10a4af97087a0bce60ac412b8 -->
## Resign roles to user

更新管理员角色
Update user roles in storage.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/manager/nostrum"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "nihil",
    "roles": [
        8
    ]
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->put(
    'https://www.blog1997.com/api/admin/manager/nostrum',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'email' => 'nihil',
            'roles' => [
                8,
            ],
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "管理员修改成功",
    "data": {
        "id": 16,
        "name": "Master",
        "avatar": "",
        "email": "15163364725@163.com",
        "article_sum": 0,
        "gender": "keep_secret",
        "roles": [
            {
                "id": 1,
                "name": "Master",
                "pivot": {
                    "user_id": 16,
                    "role_id": 1,
                    "created_at": "2021-01-22 21:38:27",
                    "updated_at": "2021-01-22 21:38:27"
                }
            },
            {
                "id": 3,
                "name": "Author",
                "pivot": {
                    "user_id": 16,
                    "role_id": 3,
                    "created_at": "2021-04-18 17:24:28",
                    "updated_at": "2021-04-18 17:24:28"
                }
            }
        ]
    }
}
```

### HTTP Request
`PUT api/admin/manager/{manager}`

`PATCH api/admin/manager/{manager}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `manager` |  optional  | 用户ID
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | 用户邮箱
        `roles` | array |  required  | 角色ID列表
        `roles.*` | integer |  required  | 角色ID
    
<!-- END_724f6ec10a4af97087a0bce60ac412b8 -->

#Sensitive word management


敏感词汇管理
<!-- START_ccf8fe01ec9326a9753bb61772b794ad -->
## Import sensitive words from file

批量导入敏感词汇
文件格式
   word1
   word2

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/sensitive-word/import"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/sensitive-word/import',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/admin/sensitive-word/import`


<!-- END_ccf8fe01ec9326a9753bb61772b794ad -->

<!-- START_7ca4517ce8482e791a7cdbf9c4405cd0 -->
## Destroy specified sensitive words

批量删除敏感慈湖

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/sensitive-word/batch-delete"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "ids": [
        6
    ]
}

fetch(url, {
    method: "DELETE",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->delete(
    'https://www.blog1997.com/api/admin/sensitive-word/batch-delete',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'ids' => [
                6,
            ],
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`DELETE api/admin/sensitive-word/batch-delete`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `ids` | array |  required  | 敏感词汇ID列表
        `ids.*` | integer |  required  | 敏感词汇ID
    
<!-- END_7ca4517ce8482e791a7cdbf9c4405cd0 -->

<!-- START_584e72c1da197a2d1ba8fa9f7231303b -->
## Display sensitive work

显示所有的敏感词汇

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/sensitive-word"
);

let params = {
    "category_id": "perspiciatis",
    "word": "aut",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/sensitive-word',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'query' => [
            'category_id'=> 'perspiciatis',
            'word'=> 'aut',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "pagination": {
            "total": 1,
            "uri": "?",
            "currentPage": 1,
            "next": 1,
            "previous": null,
            "first": 1,
            "last": 1,
            "pages": 1
        },
        "records": [
            {
                "id": 11,
                "word": "sb",
                "category_id": 27,
                "editAble": 0
            }
        ],
        "categoryList": [
            {
                "id": 0,
                "name": "所有分类"
            },
            {
                "id": 27,
                "name": "色情词库"
            }
        ],
        "categoryId": 0
    }
}
```

### HTTP Request
`GET api/admin/sensitive-word`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `category_id` |  optional  | 敏感词汇分类ID
    `word` |  optional  | 词汇

<!-- END_584e72c1da197a2d1ba8fa9f7231303b -->

<!-- START_eee3710f88a7bbcf11aa79ccf3317369 -->
## Store newly create word

添加新的敏感词汇

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/sensitive-word"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "category_id": 12,
    "word": "eveniet"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/sensitive-word',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'category_id' => 12,
            'word' => 'eveniet',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "敏感词添加成成功",
    "data": {
        "category_id": 27,
        "word": "122",
        "updated_at": "1618840963",
        "created_at": "1618840963",
        "id": 553,
        "editAble": false
    }
}
```

### HTTP Request
`POST api/admin/sensitive-word`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `category_id` | integer |  required  | 敏感词汇分类ID
        `word` | string |  required  | 敏感词汇
    
<!-- END_eee3710f88a7bbcf11aa79ccf3317369 -->

<!-- START_2102a3f7d5c37416d65e4b44c99bb3fa -->
## Update the specific word

更新敏感词汇

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/sensitive-word/doloribus"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "category_id": 8,
    "word": "qui"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->put(
    'https://www.blog1997.com/api/admin/sensitive-word/doloribus',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'category_id' => 8,
            'word' => 'qui',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "敏感词汇更新成功",
    "data": {
        "id": 553,
        "word": "1222",
        "category_id": 27,
        "created_at": "1618840963",
        "updated_at": "1618841181",
        "editAble": false
    }
}
```

### HTTP Request
`PUT api/admin/sensitive-word/{word}`

`PATCH api/admin/sensitive-word/{word}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `word` |  optional  | 敏感词汇ID
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `category_id` | integer |  required  | 敏感词汇分类ID
        `word` | string |  required  | 敏感词汇
    
<!-- END_2102a3f7d5c37416d65e4b44c99bb3fa -->

<!-- START_c41e5f9c5b23a5a07118ef77fc0d6081 -->
## Destroy the specific sensitive word

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/sensitive-word/numquam"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->delete(
    'https://www.blog1997.com/api/admin/sensitive-word/numquam',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "敏感词删除成功",
    "data": ""
}
```

### HTTP Request
`DELETE api/admin/sensitive-word/{word}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `word` |  optional  | 敏感词汇ID

<!-- END_c41e5f9c5b23a5a07118ef77fc0d6081 -->

<!-- START_99a5130bd70c86272e692d8c8280e302 -->
## Display category of sensitive word

获取敏感词汇分离列表

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/sensitive-word-category"
);

let params = {
    "name": "est",
    "rank": "eveniet",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/sensitive-word-category',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'query' => [
            'name'=> 'est',
            'rank'=> 'eveniet',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "pagination": {
            "total": 1,
            "uri": "?",
            "currentPage": 1,
            "next": null,
            "previous": null,
            "first": 1,
            "last": 1,
            "pages": 1
        },
        "records": [
            {
                "id": 27,
                "name": "色情词库",
                "count": 552,
                "rank": 1,
                "editAble": 0
            }
        ]
    }
}
```

### HTTP Request
`GET api/admin/sensitive-word-category`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `name` |  optional  | 分类名称
    `rank` |  optional  | 屏蔽等级, 1:替换,2:审批,3:拒绝

<!-- END_99a5130bd70c86272e692d8c8280e302 -->

<!-- START_9ce4c94dcb746b72cb73f6493d511b89 -->
## Store newly created category of sensitive work

新建敏感词汇分类

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/sensitive-word-category"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "est",
    "rand": 932410.991
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/sensitive-word-category',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'name' => 'est',
            'rand' => 932410.991,
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "分类添加成功",
    "data": {
        "rank": 1,
        "name": "分类",
        "updated_at": "1618840335",
        "created_at": "1618840335",
        "id": 28,
        "editAble": false
    }
}
```

### HTTP Request
`POST api/admin/sensitive-word-category`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | 分类名称,名称是唯一的
        `rand` | number |  required  | 屏蔽的级别,1:替换,2:审批,3:拒绝
    
<!-- END_9ce4c94dcb746b72cb73f6493d511b89 -->

<!-- START_6a7ff64ca37693ecbaeebf1f3c31883d -->
## Update the specific category of sensitive word

更新指定的分类

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/sensitive-word-category/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "category": "sit",
    "name": "magnam",
    "rand": 1268182.5883274
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->put(
    'https://www.blog1997.com/api/admin/sensitive-word-category/1',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'category' => 'sit',
            'name' => 'magnam',
            'rand' => 1268182.5883274,
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "分类修改成功",
    "data": {
        "id": 28,
        "name": "分类-",
        "rank": 1,
        "count": 0,
        "created_at": "1618840335",
        "updated_at": "1618840456",
        "editAble": false
    }
}
```

### HTTP Request
`PUT api/admin/sensitive-word-category/{category}`

`PATCH api/admin/sensitive-word-category/{category}`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `category` | 敏感词汇分类ID |  optional  | 
        `name` | string |  required  | 分类名称,名称是唯一的
        `rand` | number |  required  | 屏蔽的级别,1:替换,2:审批,3:拒绝
    
<!-- END_6a7ff64ca37693ecbaeebf1f3c31883d -->

<!-- START_f144d2d7caec95f3ea1b91e736f883a1 -->
## Destroy the specific category of sensitive work

删除敏感词汇分类,同时该分类下所有的词汇也会被删除

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/sensitive-word-category/officiis"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->delete(
    'https://www.blog1997.com/api/admin/sensitive-word-category/officiis',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "分类修改成功",
    "data": {
        "id": 28,
        "name": "分类-",
        "rank": 1,
        "count": 0,
        "created_at": "1618840335",
        "updated_at": "1618840456",
        "editAble": false
    }
}
```

### HTTP Request
`DELETE api/admin/sensitive-word-category/{category}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `category` |  optional  | 敏感词汇分类ID

<!-- END_f144d2d7caec95f3ea1b91e736f883a1 -->

#System config management


系统配置管理
<!-- START_07488c26d6b85ec9f67775375ef85769 -->
## Display system config

显示系统配置信息

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/system-setting"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/system-setting',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "id": 1,
        "enable_comment": "yes",
        "verify_comment": "yes"
    }
}
```

### HTTP Request
`GET api/admin/system-setting`


<!-- END_07488c26d6b85ec9f67775375ef85769 -->

<!-- START_cc6c95e3174eb8a2671447bc676b84a3 -->
## Update system config.

更新系统的配置

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/system-setting/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "enable_comment": "id",
    "verify_comment": "quas"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->put(
    'https://www.blog1997.com/api/admin/system-setting/1',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'enable_comment' => 'id',
            'verify_comment' => 'quas',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`PUT api/admin/system-setting/{system_setting}`

`PATCH api/admin/system-setting/{system_setting}`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `enable_comment` | string |  required  | 是否开启评论,例如:yes,no
        `verify_comment` | string |  required  | 是否审核评论,例如:yes,no
    
<!-- END_cc6c95e3174eb8a2671447bc676b84a3 -->

#Tag management


文章标签管理
<!-- START_f583a2631b29b67843cfd1c6e0bed46f -->
## Display article tag records.

获取标签列表

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/tag"
);

let params = {
    "parent_id": "quia",
    "name": "quisquam",
    "p": "tempora",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/tag',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'query' => [
            'parent_id'=> 'quia',
            'name'=> 'quisquam',
            'p'=> 'tempora',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": {
        "pagination": {
            "total": 2,
            "uri": "?",
            "currentPage": 1,
            "next": null,
            "previous": null,
            "first": 1,
            "last": 1,
            "pages": 1
        },
        "records": [
            {
                "id": 2,
                "name": "前端",
                "cover": "\/image\/tag\/2020-12-22\/201.701749001615fe16b55ab5449.42606432.jpg",
                "parent_id": 0,
                "description": "好~",
                "created_at": "1608608597"
            },
            {
                "id": 3,
                "name": "Vue",
                "cover": "\/image\/tag\/2020-12-22\/48.4479190016095fe16dee6d5bc7.92075244.png",
                "parent_id": 2,
                "description": "",
                "created_at": "1608609262"
            }
        ]
    }
}
```

### HTTP Request
`GET api/admin/tag`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `parent_id` |  optional  | 父标签ID
    `name` |  optional  | 标签名称
    `p` |  optional  | 请求的页数

<!-- END_f583a2631b29b67843cfd1c6e0bed46f -->

<!-- START_15c4ae3a2b2aa2981a13035cd2d6c3fc -->
## Show the resource for creating a new resource.

创建标签的时候,获取顶级标签

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/tag/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/admin/tag/create',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "success",
    "data": [
        {
            "id": 2,
            "name": "前端"
        },
        {
            "id": 16,
            "name": "后端"
        },
        {
            "id": 19,
            "name": "Linux"
        }
    ]
}
```

### HTTP Request
`GET api/admin/tag/create`


<!-- END_15c4ae3a2b2aa2981a13035cd2d6c3fc -->

<!-- START_e051551597f1c09ed6a9d54be884c0b7 -->
## Store newly created resource in storage.

新建标签
如果标签上传图片,保存之,然后备份一个webp格式图片

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/tag"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "corporis",
    "cover": "rerum",
    "parent_id": 2,
    "description": "omnis"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/tag',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'name' => 'corporis',
            'cover' => 'rerum',
            'parent_id' => 2,
            'description' => 'omnis',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "标签添加成功",
    "data": {
        "name": "ThinkPHP",
        "cover": "\/image\/tag\/2021-04-19\/128.84250900162607d9536cdb1d2.31715669.png",
        "parent_id": 0,
        "description": "",
        "updated_at": "1618842935",
        "created_at": "1618842935",
        "id": 20
    }
}
```

### HTTP Request
`POST api/admin/tag`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | 标签名称
        `cover` | string|image |  optional  | 标签封面,当封面是图片的时候,自动保存图片,并返回图片保存的路径
        `parent_id` | integer |  required  | 标签父ID,-1表示用户创建的自定义标签,0表示管理员创建的顶级标签,>1表示管理员创建的子级标签
        `description` | string |  optional  | 标签的描述
    
<!-- END_e051551597f1c09ed6a9d54be884c0b7 -->

<!-- START_3d1f7634fd2cc3fceec34cd8b524bb9b -->
## Update the specified resource in storage.

更新标签
如果有更新封面行为,会移除之前的封面

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/tag/eum"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "architecto",
    "cover": "animi",
    "parent_id": 20,
    "description": "voluptatem"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->put(
    'https://www.blog1997.com/api/admin/tag/eum',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'name' => 'architecto',
            'cover' => 'animi',
            'parent_id' => 20,
            'description' => 'voluptatem',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "标签修改成功",
    "data": {
        "id": 20,
        "name": "ThinkPHP",
        "cover": "\/image\/tag\/2021-04-19\/128.84250900162607d9536cdb1d2.31715669.png",
        "parent_id": 0,
        "description": "11",
        "user_id": 0,
        "created_at": "1618842935",
        "updated_at": "1618843304"
    }
}
```

### HTTP Request
`PUT api/admin/tag/{tag}`

`PATCH api/admin/tag/{tag}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `tag` |  optional  | 标签ID
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | 标签名称
        `cover` | string|image |  optional  | 标签封面,当封面是图片的时候,自动保存图片,并返回图片保存的路径
        `parent_id` | integer |  required  | 标签父ID,-1表示用户创建的自定义标签,0表示管理员创建的顶级标签,>1表示管理员创建的子级标签
        `description` | string |  optional  | 标签的描述
    
<!-- END_3d1f7634fd2cc3fceec34cd8b524bb9b -->

#User Login

Login user
<!-- START_31891a0c40b657fa1789125f120e382c -->
## 通过第三方账号登陆

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/oauth/authorize',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/oauth/authorize`


<!-- END_31891a0c40b657fa1789125f120e382c -->

<!-- START_a925a8d22b3615f12fca79456d286859 -->
## Handle a login request to the application.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/auth/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/auth/login',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/auth/login`


<!-- END_a925a8d22b3615f12fca79456d286859 -->

<!-- START_2f130a723cf032708bd9363d9192cacf -->
## Log the user out of the application.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/oauth/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/oauth/logout',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/oauth/logout`


<!-- END_2f130a723cf032708bd9363d9192cacf -->

#User management


用户管理
<!-- START_49a45a22313e1917be4d1345ac380afc -->
## Send a reset link to the given user.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/user/password/reset"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/user/password/reset',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/user/password/reset`


<!-- END_49a45a22313e1917be4d1345ac380afc -->

<!-- START_df191a2a142fda5fbd5e503647db0149 -->
## Reset user avatar

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/user/update/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/user/update/1',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/user/update/{user}`


<!-- END_df191a2a142fda5fbd5e503647db0149 -->

<!-- START_597a6c8e9091d11b064ea3b760a1426a -->
## Bind social account to exists account

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/user/bind"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/user/bind',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/user/bind`


<!-- END_597a6c8e9091d11b064ea3b760a1426a -->

<!-- START_4e3ba0223ef9910024ffdb04c97a192f -->
## Bind social account to exists account

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/user/unbind/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/user/unbind/1',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/user/unbind/{account}`


<!-- END_4e3ba0223ef9910024ffdb04c97a192f -->

<!-- START_27957f32f7f139da93ef9bd9f03bbc31 -->
## Bind social account to exists account

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/user/rebind"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/user/rebind',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/user/rebind`


<!-- END_27957f32f7f139da93ef9bd9f03bbc31 -->

<!-- START_a4251b7143964e3f7d9fb181a7b86ba5 -->
## Get user extra information

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/user/profile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/user/profile',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`GET api/user/profile`


<!-- END_a4251b7143964e3f7d9fb181a7b86ba5 -->

<!-- START_4bb7fb4a7501d3cb1ed21acfc3b205a9 -->
## destroy

注销账号
Remove the specified resource from storage.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/user/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->delete(
    'https://www.blog1997.com/api/user/1',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`DELETE api/user/{user}`


<!-- END_4bb7fb4a7501d3cb1ed21acfc3b205a9 -->

#general


<!-- START_7be72b842de762e9c78280bd09d963a2 -->
## get CAPTCHA api

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/captcha/api/"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/captcha/api/',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`GET captcha/api/{config?}`


<!-- END_7be72b842de762e9c78280bd09d963a2 -->

<!-- START_ac3d8e4500522f6ab8e01138d80565d5 -->
## get CAPTCHA

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/captcha/"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/captcha/',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`GET captcha/{config?}`


<!-- END_ac3d8e4500522f6ab8e01138d80565d5 -->

<!-- START_7829995d1ce226a8e0477abcc75255c7 -->
## 获取当前登陆的用户

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/oauth/currentUser"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/api/oauth/currentUser',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`GET api/oauth/currentUser`

`POST api/oauth/currentUser`

`PUT api/oauth/currentUser`

`PATCH api/oauth/currentUser`

`DELETE api/oauth/currentUser`

`OPTIONS api/oauth/currentUser`


<!-- END_7829995d1ce226a8e0477abcc75255c7 -->

<!-- START_53acde0c31e81a23a0684b2bba03341a -->
## Update Manager password

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/auth/manager/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->put(
    'https://www.blog1997.com/api/auth/manager/1',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`PUT api/auth/manager/{manager}`


<!-- END_53acde0c31e81a23a0684b2bba03341a -->

<!-- START_985479192c13809866927063d1da3810 -->
## Resend the email verification notification.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/oauth/sign-up"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/oauth/sign-up',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/oauth/sign-up`


<!-- END_985479192c13809866927063d1da3810 -->

<!-- START_443b57a212b491ec6d333fc9b317b297 -->
## Upload some image

上传图片
并且会生成对应的webp格式的图片和一个等比缩放,宽度为100的缩略图

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/upload/image/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/upload/image/1',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": "success",
    "message": "图片上传成功",
    "data": [
        "\/image\/article\/2021-04-19\/601.99668700162607d9f69f35626.21398016.jpg?width=6000&height=4000"
    ]
}
```

### HTTP Request
`POST api/admin/upload/image/{category}`


<!-- END_443b57a212b491ec6d333fc9b317b297 -->

<!-- START_d216d14e2b16b5fbcefd2380b0a58261 -->
## Reset the given user&#039;s password.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/api/admin/password/update"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->post(
    'https://www.blog1997.com/api/admin/password/update',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/admin/password/update`


<!-- END_d216d14e2b16b5fbcefd2380b0a58261 -->

<!-- START_d7e4f5d89430833b89de44ea7be94f16 -->
## Show create Page

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/admin/manager/register"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/admin/manager/register',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`GET admin/manager/register`


<!-- END_d7e4f5d89430833b89de44ea7be94f16 -->

<!-- START_06e97debabe4ca828b36b06a792cbc20 -->
## Mark the authenticated user&#039;s email address as verified.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/admin/verify"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/admin/verify',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`GET admin/verify`


<!-- END_06e97debabe4ca828b36b06a792cbc20 -->

<!-- START_03a76d7b7a89853a08696bfe71bbbba7 -->
## admin/login
> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/admin/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/admin/login',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`GET admin/login`


<!-- END_03a76d7b7a89853a08696bfe71bbbba7 -->

<!-- START_9d4cb50a9a97c999fcb3fd8be2b6e73c -->
## admin/login/{any}
> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/admin/login/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/admin/login/1',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`GET admin/login/{any}`


<!-- END_9d4cb50a9a97c999fcb3fd8be2b6e73c -->

<!-- START_583a6990174e55a2eb91791ae4776c83 -->
## Display the password reset view for the given token.

If no token is present, display the link request form.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/admin/password/reset"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/admin/password/reset',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`GET admin/password/reset`


<!-- END_583a6990174e55a2eb91791ae4776c83 -->

<!-- START_f64d3ae812892c1fb55f58fef06120f1 -->
## index
Method GET

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/admin/"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/admin/',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`GET admin/{any?}`


<!-- END_f64d3ae812892c1fb55f58fef06120f1 -->

<!-- START_cb859c8e84c35d7133b6a6c8eac253f8 -->
## Show the application dashboard.

> Example request:

```javascript
const url = new URL(
    "https://www.blog1997.com/home"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    'https://www.blog1997.com/home',
    [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`GET home`


<!-- END_cb859c8e84c35d7133b6a6c8eac253f8 -->


