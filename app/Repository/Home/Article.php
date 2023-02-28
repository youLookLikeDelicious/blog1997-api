<?php

namespace App\Repository\Home;

use App\Repository\Comment;
use App\Facades\CacheModel;
use App\Models\Article as ArticleModel;
use App\Contract\Repository\Article as RepositoryArticle;
use App\Contract\Repository\Tag;
use App\Facades\HighLightHtml;
use App\Http\Resources\CommonCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Article implements RepositoryArticle
{
    /**
     * Article eloquent
     * @var \App\Models\Article
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
        $id       = $this->decodeArticleId($id);
        $seconds  = getCacheSeconds(60 + mt_rand(0, 100));
        $isWechat = request('wechat') ?: '';

        $articleRecord = Cache::remember("article-$id-$isWechat", $seconds, function () use ($id, $isWechat) {
            $article =  $this->article
                ->withAuthorAndGalleryAndThumbs()
                ->with('tags:id,name')
                ->selectRaw('id, content, user_id, title, gallery_id, visited, commented, liked, created_at, is_markdown, updated_at')
                ->where('id', $id)
                ->where('is_draft', 'no')
                ->first();

            if ($isWechat) {
                $article->content = HighLightHtml::make($article->content, $article->is_markdown === 'yes');
            }

            return $article;
        });

        if (!$articleRecord) {
            throw new ModelNotFoundException;
        }
        
        $this->syncArticleCache(collect([$articleRecord]));

        $articleRecord->makeHidden(['id'])->append('identity');
        
        $articleRecord = $articleRecord->toArray();
        $articleRecord['thumbs'] = !empty($articleRecord['thumbs']);

        $result = [
            'article' => $articleRecord,
            'commented' => $articleRecord['commented']
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
        $comments = $this->comment->getComment($id, 'article');

        return $comments;
    }

    /**
     * 获取文章列表,默认每页查询10条记录
     * 
     * @param Request $request
     * 
     * @return \App\Http\Resources\CommonCollection
     */
    public function all(Request $request)
    {
        $this->validateRequest($request);

        // 获取文章的内容        
        $articleQuery = $this->buildGetAllArticleQuery($request);

        $result = $articleQuery->paginate($request->input('perPage', 10));
        
        $this->syncArticleCache($result);

        $result->makeHidden(['id']);

        return new CommonCollection($result);
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
            'tag_id' => 'sometimes',
            'limit' => 'sometimes|required|numeric|max:20',
            'keyword' => 'sometimes'
        ]);
    }

    /**
     * Decode article id
     *
     * @param string $id
     * @return int
     * @throws NotFoundHttpException
     */
    public function decodeArticleId($id)
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
            $articleQuery->whereRaw('Match (title, content) AGAINST ("+'. $keyword .'>" IN BOOLEAN MODE)');
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
        $popArticle = $this->article->selectRaw('id, title, visited, created_at')
            ->orderBy('visited', 'DESC')
            ->where('is_draft', 'no')
            ->limit(7)
            ->get();
        $popArticle->each(function ($article) {
            $article->append(['identity']);
            $article->makeHidden('id');
        });
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
     * 同步文章相关的缓存数据
     *
     * @return void
     */
    protected function syncArticleCache($articles)
    {
        $articles->each(function ($article) {
            // 获取点赞的数量
            if ($cachedLiked = CacheModel::getArticleLiked($article->id)) {
                $article['liked'] += $cachedLiked;
            }

            // 获取访问量
            if ($cachedVisited = CacheModel::getArticleVisited($article->id)) {
                $article['visited'] += $cachedVisited;
            }

            // 获取评论的数量
            if ($cachedCommented = CacheModel::getArticleCommented($article->id)) {
                $article['commented'] += $cachedCommented;
            }
        });
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

        $resource = $this->all($request);

        $resource->additional([
            'tags' => $tags,
            'currentTagId' => $request['tag_id'] + 0
        ]);

        return $resource;
    }
}
