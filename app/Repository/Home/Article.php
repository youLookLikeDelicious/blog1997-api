<?php

namespace App\Repository\Home;

use App\Facades\Page;
use App\Repository\Comment;
use App\Facades\CacheModel;
use App\Model\Article as ArticleModel;
use App\Contract\Repository\Article as RepositoryArticle;
use App\Contract\Repository\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Article implements RepositoryArticle
{
    /**
     * Article eloquent
     * @var App\Model\Article
     */
    protected $article;

    /**
     * Comment repository
     *
     * @var Comment
     */
    protected $comment;

    /**
     * Tag repository
     *
     * @var Tag
     */
    protected $tag;

    public function __construct(ArticleModel $article, Comment $comment, Tag $tag)
    {
        $this->article = $article;

        $this->comment = $comment;

        $this->tag = $tag;
    }

    /**
     * 获取文章的详情
     * 
     * @param string $id
     * @return array
     */
    public function find($id): array
    {
        $id = $this->decodeArticleId($id);
        
        $articleRecord = Cache::remember('article-' . $id, (5 * 60 * 60 + mt_rand(0, 500)), function () use ($id) {
            return $this->article
                ->withAuthorAndGalleryAndThumbs()
                ->with('tags:id,name')
                ->selectRaw('to_base64(id) as identity, id, content, user_id, title, gallery_id, visited, commented, liked, created_at, is_markdown, updated_at')
                ->findOrFail($id);
        });

        $articleRecord->makeHidden(['id']);

        $articleRecord = $articleRecord->toArray();

        $articleRecord['thumbs'] = !empty($articleRecord['thumbs']);

        // 获取点赞的数量
        if ($cachedLiked = CacheModel::getArticleLiked($id)) {
            $articleRecord['liked'] += $cachedLiked;
        }

        // 获取评论的数量
        if ($cachedCommented = CacheModel::getArticleCommented($id)) {
            $articleRecord['commented'] += $cachedCommented;
        }

        $result = [
            'article' => $articleRecord,
            'commented' => $articleRecord['commented'],
            'comments' => []
        ];

        return $result;
    }

    /**
     * 获取文章相关的评论
     *
     * @param string $id
     * @return array
     */
    public function comments($id)
    {
        $id = $this->decodeArticleId($id);

        // 获取评论
        $comments = $this->comment->getComment($id, ArticleModel::class);

        return $comments + ['articleId' => $id];
    }

    /**
     * 获取文章列表,默认每页查询10条记录
     * 
     * @param Request $request
     * @return array
     */
    public function all(Request $request): array
    {
        $this->validateRequest($request);

        // 获取文章的内容        
        $articleQuery = $this->buildGetAllArticleQuery($request);

        $articles = Page::paginate($articleQuery, $request->input('limit', 10));

        $articles['records']->makeHidden(['id']);
        $articles['articles'] = $articles['records'];

        $articles['pages'] = $articles['pagination']['pages'];
        $articles['p'] = $articles['pagination']['currentPage'];
        $articles['articleNum'] = $articles['pagination']['total'];

        unset($articles['records']);
        unset($articles['pagination']);

        return $articles;
    }

    /**
     * 验证传入的request
     *
     * @param Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateRequest(Request $request)
    {
        $request->validate([
            'order_by' => 'sometimes|in:visit,commented,new,mixed',
            'tag_id' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|required|numeric|max:20',
            'keyword' => 'sometimes|required'
        ]);
    }

    /**
     * Decode article id
     *
     * @param string $id
     * @return int
     * @throws NotFoundHttpException
     */
    protected function decodeArticleId($id)
    {
        $id = base64_decode($id);

        if (!is_numeric($id)) {
            abort(404);
        }

        return $id;
    }

    /**
     * 创建获取所有文章的query
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildGetAllArticleQuery(Request $request)
    {
        $articleQuery = $this
            ->article
            ->withAuthorAndGallery()
            ->with('tags:id,name')
            ->selectRaw('to_base64(`id`) as `identity`, id, title, is_origin, user_id, is_markdown, summary, visited, gallery_id, commented, created_at, updated_at');

        // 设置标签id
        if ($tagId = $request->input('tag_id')) {
            $articleQuery->whereHas('tags', function (Builder $query) use ($tagId) {
                $query->where('id', $tagId);
                    // ->where('parent_id', '>=', 0);
            });
        }

        // 设置排序的方式
        switch ($request->input('order_by')) {
            case 'visit':
                $articleQuery = $articleQuery->orderBy('visited', 'DESC');
                break;
            case 'commented':
                $articleQuery = $articleQuery->orderBy('commented', 'DESC');
                break;
            case 'new':
                $articleQuery = $articleQuery->orderBy('created_at', 'DESC');
                break;
            case 'mixed':
                $articleQuery = $articleQuery->orderBy('created_at', 'DESC')
                    ->orderBy('commented', 'DESC')
                    ->orderBy('visited', 'DESC');
                break;
            default:
                $articleQuery = $articleQuery->orderBy('created_at', 'DESC')
                    ->orderBy('updated_at', 'DESC');
                break;
        }

        // 配置全文索引的内容
        if ($keyword = $request->input('keyword')) {
            $articleQuery->whereRaw('Match (title, content) AGAINST ("'. $keyword .'<" IN BOOLEAN MODE)');
        }

        $articleQuery->where('is_draft', 'no');

        $articleQuery->orderBy('order_by', 'DESC');

        return $articleQuery;
    }

    /**
     * 获取最受欢迎的Top10
     * 
     * @return array
     */
    public function getTopTen(): array
    {
        $popArticle = $this->article->selectRaw('to_base64(id) as id, title, visited, created_at')
            ->orderBy('visited', 'DESC')
            ->limit(7)
            ->get();

        return $popArticle->toArray();
    }

    /**
     * 获取文章的数量
     *
     * @return int
     */
    public function getArticleCount(): int
    {
        $result = $this->article
            ->selectRaw('count(id) as count')
            ->where('is_draft', 'no')
            ->first();

        return $result->count ?? 0;
    }

    /**
     * 获取标签以及文章
     *
     * @param Request $request
     * @return array
     */
    public function tags(Request $request)
    {
        // 获取所有标签
        $tags = $this->tag->all($request);

        if (!count($tags)) {
            return [];
        }

        if (!$request->input('tag_id')) {
            $request->request->add(['tag_id' => $tags[0]['id']]);
        }

        $data = $this->all($request);

        $data['tags'] = $tags;

        if ($tag = $request['tag_id']) {
            $data['currentTagId'] = $tag + 0;
        }

        return $data;
    }
}
