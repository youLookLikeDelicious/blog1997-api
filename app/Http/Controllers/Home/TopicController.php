<?php

namespace App\Http\Controllers\Home;


use App\Model\Topic;
use App\Model\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TopicController extends Controller
{
    /**
     * 获取专题相关信息
     * @param Request $request
     * @param Article $article
     * @param String $topicId
     * @return mixed
     */
    public function index (Request $request, Article $article, $topicId = '') {

        if ($topicId){
            $topicId = base64_decode($topicId);
        }

        // 获取专题
        $topics = Topic::select('id', 'name', 'article_sum', 'created_at')->get();

        // 没有专题
        if (!$topics->count()) {
            return response()->success(['topics' => [], 'articles' => [], 'total' => 0]);
        }

        // 获取专题下相关的文章
        $relations = [
            'user' => function ($query) {
                $query->select(['id', 'name', 'avatar']);
            },
            'gallery' => function ($query) {
                $query->select('url', 'id');
            }
        ];

        $articleBuilder =$article::with($relations)
            ->selectRaw('to_base64(id) as id, title, is_origin, user_id, summary, visited, gallery_id, commented, created_at, keywords');
        
        // 如果指定专题ID，搜索指定的专题
        if ( is_numeric($topicId) ) {
            $curTopicId = $topicId;
        } else {
            // 如果没有指定专题ID，使用专题列表第一个ID
            $curTopicId = $topics[0]->id;
        }
        
        $articleBuilder->where('topic_id', $curTopicId);

        // 分页获取文章
        $articles = \Page::paginate($articleBuilder);

        $result = [
            'topics' => $topics,
            'articles' => $articles['records'],
            'pages' => $articles['pagination']['pages'],
            'total' => $articles['pagination']['total'],
            'curTopicId' => $curTopicId
        ];

        return response()->success($result);
    }
}
