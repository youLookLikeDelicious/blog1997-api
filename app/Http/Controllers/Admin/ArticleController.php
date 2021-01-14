<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\ArticleBackUp;
use App\Contract\Repository\Tag;
use App\Contract\Repository\Topic;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Model\ArticleBase as Article;
use App\Model\Article as ModelArticle;
use App\Http\Requests\Admin\Article as ArticleRequest;
use App\Contract\Repository\Article as ArticleRepository;

class ArticleController extends Controller
{
    /**
     * repository
     *
     * @var /App\Contract\Repository\Article
     */
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;

        $this->authorizeResource(Article::class, 'article');
    }
    
    /**
     * 获取文章列表
     * Method GET
     * 
     * @param $topicId
     * @return \Illuminate\Http\Response
     */
    public function index (Request $request) {
        $data = $this->articleRepository->all($request);

        return response()->success($data);
    }

    /**
     * 获取文章的内容
     * Method POST
     * @return mixed
     */
    public function show (?Article $article) {
        
        return response()->success($article);
    }

    /**
     * 新建 文章
     * Method POST
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function store (ArticleRequest $request, ModelArticle $article) {

        // 获取表单数据
        $data = $request->validated(); 
        
        $tags = $data['tags'] ?? [];

        if ($tags) {
            unset($data['tags']);
        }

        $articleRecord = '';

        DB::transaction(function () use ($article, $data, &$articleRecord, $tags) {
            
            $articleRecord = $article->create($data);

            // 添加多对多的关系
            $articleRecord->tags()->sync($tags);
        });
        
        $message = $articleRecord->is_draft === 'yes' ? '草稿保存成功' : '文章创建成功';

        $articleRecord->load('tags:id,name');
        
        return response()->success($articleRecord, $message);
    }

    /**
     * 更新文章 | 发布草稿
     * Method PUT
     *
     * @param ArticleRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
        // 修改操作,会触发observer
        $data = $request->validated();

        $tags = [];

        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }
        
        DB::transaction(function () use(&$article, $data, $tags) {
            // 将原稿保存为草稿后，返回草稿
            if (!$article->update($data)) {
                return;
            }

            // 添加多对多的关系
            $article->tags()->sync($tags);
        });

        $message = '文章发布成功';

        if ($article->isDraft()) {
            $article = $this->articleRepository->findDraft($article);
            $message = '草稿保存成功';
        }

        return response()->success($article, $message);
    }

    /**
     * 删除文章
     * Method POST
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy (Article $article)
    {
        if ($article->is_draft === 'yes') {
            // 删除草稿不需要执行Observer
            ModelArticle::where('id', $article->id)->delete();
        } else {
            DB::transaction(function () use ($article) {
                $article->delete();
            });
        }

        return response()->success('', '文章删除成功');
    }

    /**
     * Get topics and tags
     *
     * @param Topic $topic
     * @param Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function create(Topic $topic, Tag $tag)
    {
        $result = [];
        $result['topics'] = $topic->all();
        $result['tags'] = $tag->flatted();

        return response()->success($result);
    }

    /**
     * Restore deleted article
     *
     * @param Article $article
     * @return \Illuminate\Http\Response
     */
    public function restore(Article $article)
    {
        ModelArticle::create($article->toArray());
        ArticleBackUp::where('id', $article->id)->delete();

        return response()->success('', '文章已从回收站恢复');
    }
}
