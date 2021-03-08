<?php

namespace App\Repository\Admin;

use App\Facades\Page;
use Illuminate\Http\Request;
use App\Model\Article as ModelArticle;
use App\Contract\Repository\Article as RepositoryArticle;
use App\Contract\Repository\ArticleBackUp;
use App\Contract\Repository\Topic;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Article implements RepositoryArticle
{
    /**
     * Article Eloquent
     *
     * @var ModelArticle
     */
    protected $model;

    /**
     * Topic repository
     * @var Topic
     */
    protected $topicRepository;

    /**
     * Article backup repository
     *
     * @var ArticleBackUp
     */
    protected $articleBackUp;

    public function __construct(ModelArticle $model, Topic $topicRepository, ArticleBackUp $articleBackUp)
    {
        $this->model = $model;
        $this->topicRepository = $topicRepository;
        $this->articleBackUp = $articleBackUp;
    }

    /**
     * 根据专题获取文章
     *
     * @param int $where topic id
     * @return array
     */
    public function all(Request $request): array
    {
        $this->validateRequest($request);
        // 获取所有的专题
        $topics = $this->topicRepository->all();

        $query = $this->generateQuery($request);

        $articles = Page::paginate($query);

        $articles['topics'] = $topics->toArray();
        array_unshift($articles['topics'], ['id' => 0, 'name' => '所有专题']);

        // 当前的专题id
        $articles['currentTopicId'] = $request->input('topicId') + 0;

        // 获取相关文章的总数量
        $total = $this->getTotalArticleCount();
        // 获取相关文章的总数量
        $draftCount = $this->getDraftArticleCount();

        // 获取相关文章的总数量
        $deletedCount = $this->articleBackUp->count();
        $articles['count'] = [
            'total' => $total,
            'draft' => $draftCount,
            'deleted' => $deletedCount
        ];

        return $articles;
    }

    /**
     * 验证传入的request请求
     *
     * @param Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateRequest(Request $request)
    {
        $request->validate([
            'topicId' => 'sometimes|integer'
        ]);
    }

    /**
     * 生成请求Mysql 的query
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function generateQuery(Request $request)
    {
        $type = $request->input('type');

        // 从回收站中获取文章
        if ($type === 'deleted') {
            return $this->articleBackUp->generateQuery();
        }

        $query = $this->model
            ->select(['id', 'title', 'is_origin', 'visited', 'commented', 'liked', 'updated_at', 'user_id', 'is_draft'])
            ->where('user_id', auth()->id());

        if ($topicId = $request->input('topicId')) {
            $query->where('topic_id', $topicId);
        }

        // 设置排序的方式
        switch ($request->input('order-by', 'new')) {
            case 'hot':
                $query->orderBy('visited', 'DESC');
                break;
            case 'new':
            default:
                $query->orderBy('updated_at', 'DESC');
        }

        if ($type === 'draft') {
            $query->where('is_draft', 'yes');
        }

        return $query;
    }

    /**
     * get article by id
     *
     * @param int $id
     * @return ModelArticle
     */
    public function find($id)
    {
        $article = $this->model->select(['id', 'title', 'gallery_id', 'is_origin', 'topic_id', 'is_markdown', 'content', 'order_by', 'article_id', 'user_id', 'is_draft'])
            ->with(['tags:id', 'gallery:id,url'])
            ->findOrFail($id)
            ->toArray();

        // 获取原文章 和 tags的多对多关系
        if ($article['is_draft'] === 'yes' && $article['article_id']) {

            $originArticle = $this->model->select('id')
                ->with('tags:id')
                ->findOrFail($article['article_id']);

            $article['tags'] = $originArticle->toArray()['tags'];
        }

        return $this->model->make($article);
    }

    /**
     * 更新操作之后获取文章的草稿
     *
     * @param ModelArticle $article 文章id
     * @return ModelArticle
     */
    public function findDraft($article)
    {
        if ($article->isDraft()) {
            $article->load('tags:id');

            return $article;
        }

        // 获取草稿的id
        $draft = $this->model->select('article_id', 'id')
            ->draft($article->article_id)
            ->first();
        
        if (! $draft){
            throw new ModelNotFoundException;
        }

        return $this->find($draft->id);
    }

    /**
     * 获取最新的文章
     *
     * @return int
     */
    public function getNewestArticleGalleryId()
    {
        $article = $this->model
            ->select('gallery_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->first();

        return $article ? ($article->gallery_id ?: 0) : 0;
    }

    /**
     * 统计各个分类下文章的数量
     *
     * @return array
     */
    public function statisticByCategory()
    {
        $result = $this->model
            ->with('topic:id,name')
            ->selectRaw('count(id) as count, topic_id')
            ->groupBy('topic_id')
            ->get();

        return $result;
    }

    /**
     * Get draft article count
     *
     * @return int
     */
    protected function getDraftArticleCount()
    {
        return $this->model->selectRaw('count(id) as total')
            ->where('is_draft', 'yes')
            ->where('user_id', auth()->id())
            ->first()
            ->total;
    }

    /**
     * Get the number of articles
     *
     * @return int
     */
    protected function getTotalArticleCount()
    {
        return $this->model->selectRaw('count(id) as total')
            ->where('user_id', auth()->id())
            ->first()
            ->total;
    }
}
