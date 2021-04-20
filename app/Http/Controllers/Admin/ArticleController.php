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

/**
 * @group Article management
 * 
 * 文章管理
 */
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
     * Display article records
     * 
     * 后台获取文章列表
     * 
     * @queryParam topicId  专题id
     * @queryParam type     文章的类型,例如draft,deleted
     * @queryParam order-by 排序方式,例如hot,new,默认是new
     * @responseFile response/admin/article/index.json
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->articleRepository->all($request);

        return response()->success($data);
    }

    /**
     * Show the specific article when update
     * 
     * 获取文章的内容
     * 如果文章不属于当前用户,无法获取文章详情
     * 
     * @urlParam article required 文章的id
     * @responseFile response/admin/article/show.json
     * @return \Illuminate\Http\Response
     */
    public function show(?Article $article)
    {
        return response()->success($article);
    }

    /**
     * Store newly created article
     * 
     * 新建文章
     * 
     * @bodyParam tags     array      required 标签列表
     * @bodyParam tags.*   int|string required 当标签内容为字符串时,自动创建一个标签
     * @bodyParam title    string     required 文章的标题
     * @bodyParam content  string     required 文章内容
     * @bodyParam is_draft string     required 是否是草稿,例如yes,no
     * @bodyParam topic_id string|int required 专题id或专题名称,当该值不为int类型时,会创建一个专题
     * @bodyParam is_markdown string  required 是否是markdown格式
     * @bodyParam cover image|string  required当该值为image的时候,自动上传图片,然后使用图片URL替换
     * @responseFile response/admin/article/store.json
     * 
     * @param ArticleRequest $request
     * @param ModelArticle $article
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function store(ArticleRequest $request, ModelArticle $article)
    {

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
     * Update the specific article
     * 
     * 更新文章|草稿,发布草稿
     * 
     * 用户只能修改自己的文章
     * 如果文章包含的图片被移除,尝试从本地存储中移除该图片
     * 
     * @urlParam article   文章id
     * @bodyParam tags     array      required 标签列表
     * @bodyParam tags.*   int|string required 当标签内容为字符串时,自动创建一个标签
     * @bodyParam title    string     required 文章的标题
     * @bodyParam content  string     required 文章内容
     * @bodyParam is_draft string     required 是否是草稿,例如yes,no
     * @bodyParam topic_id string|int required 专题id或专题名称,当该值不为int类型时,会创建一个专题
     * @bodyParam is_markdown string  required 是否是markdown格式
     * @bodyParam cover image|string  required当该值为image的时候,自动上传图片,然后使用图片URL替换
     * @responseFile response/admin/article/update.json
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

        DB::transaction(function () use (&$article, $data, $tags) {
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
     * Destroy the specific article
     * 
     * 删除文章
     * 如果删除回收站中的文章,将文章彻底移除,如果删除发布的文章,将文章移动到回收站
     * 彻底删除的文章时,也会尝试删除本地保存的图片
     * 
     * @urlParam article 文章id
     * @response {
     *   data: '',
     *   message: '文章删除成功,
     *   status: 'success'
     * }
     * 
     * @param Article $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
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
     * Get topics and tags when publish article
     * 
     * 获取创建文章所需要的数据:标签和专题
     * 
     * @responseFile response/admin/article/create.json
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
     * Restore article from recycle bin
     * 
     * 还原删除的文章
     * restore deleted article
     * 
     * @urlParam article 文章id
     * @response {
     *   data: '',
     *   message: '文章已从回收站恢复,
     *   status: 'success'
     * }
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
