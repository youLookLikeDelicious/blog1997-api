<?php

namespace App\Http\Controllers\Home;

use App\Model\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 网站的搜索控制器
 */
class SearchController extends Controller
{
    /**
     * 根据标题和content搜索文章
     * @param Request $request
     * @param Article $article
     * @param String $content 搜索的关键字
     */
    public function index (Request $request, Article $article, $content = '', $orderBy = 'mixed') {
        // 搜索的内容不能为空
        if ($content === '') {
            return  $this->invalidSearch();
        }

        // 获取搜索的类型 tag | title & content
        $searchType = $request->input('searchType', 'titleAndContent');

        $where = '';
        // 构建查询的query
        switch ($searchType) {
            case 'tag': 
                $where = "Match(tag) AGAINST ('{$content}')";
            break;
            // 默认搜索的是标题和文章内容
            case 'titleAndContent' :
            default:
                $where = "Match(title, content) AGAINST('{$content}')";
            break;
        }
        
        $articles = $article->getArticleList($where, $orderBy);

        $result = [
            'articles' => $articles['records'],
            'pages' => $articles['pagination']['pages'],
            'total' => $articles['pagination']['total']
        ];
        
        return response()->success($result);
    }

    public function invalidSearch () {
        return response()->error('非法的搜索内容');
    }
}
