<?php

namespace Tests\Unit\Repository\Admin;

use App\Models\Article as ModelArticle;
use App\Models\ArticleBackUp;
use App\Models\Tag;
use App\Models\Topic as ModelTopic;
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

        $repository = new Article(new ModelArticle, app()->make(AdminArticleBackUp::class), app()->make(Topic::class));

        // 创建一个专题
        $topic = ModelTopic::factory()->create();

        // 创建一个草稿
        $draft = ModelArticle::factory()
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
        ArticleBackUp::factory()
            ->create([
                'title' => 'deleted',
                'user_id' => $this->user->id
            ]);

        // 创建一篇可见的文章
        $article = ModelArticle::factory()
            ->create([
                'id' => 99,
                'title' => 'blog1997',
                'topic_id' => $topic->id,
                'user_id' => $this->user->id,
                'article_id' => 99
            ]);

        // 创建标签
        $tag = Tag::factory()->create();
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

        $this->assertEquals('deleted', $result[0]->title);
    }
}
