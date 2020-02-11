<?php

namespace App\Http\Controllers\Home;

use App\Model\Article;
use App\Model\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * 获取文章详情 以及相关的回复
     * Method GET
     * @param Request $request
     * @param Comment $comment
     * @param Article $article
     * @param $id 文章id
     * @return mixed
     */
    public function index (Request $request, Comment $comment, Article $article, $id) {

        //  解码文章的id
        $id = base64_decode($id);

        if (is_numeric($id)) {
            $id += 0;
        } else {
            return response()->error('unknown id');
        }
        
        $articleRecord = $article->relations()
            ->select(['id', 'content', 'user_id', 'title', 'gallery_id', 'visited', 'commented', 'keywords', 'liked', 'created_at', 'updated_at'])
            ->where('id', $id)
            ->first();
        
        // 没有文章记录
        if (!$articleRecord) {
            return response()->success('文章不见了');
        }

        $articleRecord = $articleRecord->toArray();
        $articleRecord['thumbs'] = !!$articleRecord['thumbs'];


        $articleId = $articleRecord['id'];


        // 获取文章当天的访问人数
        $visited = \RedisCache::incrArticleVisited($articleId);

        // 获取当天的评论人数
        $commented = \RedisCache::getArticleCommented($articleId);

        // 获取当天的点赞数
        $liked = \RedisCache::getArticleLiked($articleId);

        if ($visited) {
            $articleRecord['visited'] += $visited;
        }

        if ($commented) {
            $articleRecord['commented'] += $commented;
        }

        if ($liked) {
            $articleRecord['liked'] = $liked;
        }

        // 获取评论
        $comments = $comment->getComment($articleRecord['id'], Article::class);

        $result = array_merge($comments, ['article' => $articleRecord, 'commented' => $articleRecord['commented']]);

        return response()->success($result);
    }

    /**
     * 获取文章列表
     * @param Request $request
     * @param Article $article
     * @return mixed
     */
    public function getList (Request $request, Article $article) {
        
        // 获取get参数
        $query = $request->input();

        $where = '';

        if (array_key_exists('topicId', $query) && is_numeric($query['topicId'])) {
            $where = "topic_id = {$query['topicId']}";
        }
        
        $article = $article->getArticleList($where);

        $result = [
            'articles' => $article['records']
        ];

        return response()->success($result);
    }
}
