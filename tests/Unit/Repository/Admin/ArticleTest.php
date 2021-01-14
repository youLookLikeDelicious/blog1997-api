<?php

namespace Tests\Unit\Repository\Admin;

use App\Model\Article as ModelArticle;
use App\Model\ArticleBackUp;
use App\Model\Tag;
use App\Model\Topic as ModelTopic;
use App\Repository\Admin\Article;
use App\Repository\Admin\ArticleBackUp as AdminArticleBackUp;
use App\Repository\Admin\Topic;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test article repository
     * @group unit
     *
     * @return void
     */
    public function test()
    {
        $this->makeUser();

        $repository = new Article(new ModelArticle, app()->make(Topic::class), app()->make(AdminArticleBackUp::class));

        // 创建一个专题
        $topic = factory(ModelTopic::class)->create();

        // 创建一个草稿
        $draft = factory(ModelArticle::class)
            ->create([
                'is_draft' => 'yes',
                'title' => 'draft',
                'id' => 1,
                'user_id' => $this->user->id
            ]);
        // article id 实在observer中赋值的
        // 需要创建完成后指定
        ModelArticle::where('id', 1)->update(['article_id' => 99]);

        // 在回收站中创建一篇文章
        factory(ArticleBackUp::class)
            ->create([
                'title' => 'deleted',
                'user_id' => $this->user->id
            ]);

        // 创建一篇可见的文章
        $article = factory(ModelArticle::class)
            ->create([
                'id' => 99,
                'title' => 'blog1997',
                'topic_id' => $topic->id,
                'user_id' => $this->user->id,
                'article_id' => 99
            ]);

        // 创建标签
        $tag = factory(Tag::class)->create();
        $article->tags()->sync([$tag->id]);

        // 获取一篇草稿
        $draft = $repository->find(1);
        // 断言草稿的tags 显示的时原文章的tags
        $this->assertEquals($tag->id, $draft->toArray()['tags'][0]['id']);

        // 通过文章原稿获取草稿
        $draft = $repository->findDraft($article);
        $this->assertEquals(1, $draft->id);

        // 获取回收站中的文章
        $request = new Request(['type' => 'deleted', 'order-by' => 'hot']);
        $result = $repository->all($request);

        $this->assertEquals('deleted', $result['records'][0]->title);
    }
}
