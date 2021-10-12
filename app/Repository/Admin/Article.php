<?php

namespace App\Repository\Admin;

use Illuminate\Http\Request;
use App\Contract\Repository\Topic;
use App\Model\Article as ModelArticle;
use App\Http\Resources\CommonCollection;
use App\Contract\Repository\ArticleBackUp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Contract\Repository\Article as RepositoryArticle;

class Article implements RepositoryArticle
{
    /**
     * Article Eloquent
     *
     * @var ModelArticle
     */
    protected $model;

    /**
     * Article backup repository
     *
     * @var ArticleBackUp
     */
    protected $articleBackUp;

    /**
     * Topic repository
     *
     * @var Topic
     */
    protected $topicRepository;

    public function __construct(ModelArticle $model, ArticleBackUp $articleBackUp, Topic $topicRepository)
    {
        $this->model = $model;
        $this->articleBackUp = $articleBackUp;
        $this->topicRepository = $topicRepository;
    }

    /**
     * 根据专题获取文章
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function all(Request $request)
    {
        $this->validateRequest($request);

        $query = $this->generateQuery($request);

        $articles = $query->paginate($request->input('perPage', 10));

        return (new CommonCollection($articles))
            ->additional([
                'meta' => [
                    'topics' => $this->topicRepository->all(),
                    'draft' => $this->getDraftArticleCount(),
                    'deleted' => $this->articleBackUp->count()
                ]
            ]);
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

}
