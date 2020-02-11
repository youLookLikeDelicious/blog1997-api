<?php

namespace App\Model;

use App\Facades\CustomAuth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Article extends Model
{
    //
    public $dateFormat = 'U';
    protected $table = 'article';
    protected $guarded = [];
    public $timestamps = true;
    protected $keyType = 'string';
    
    /**
     * 定义和作者的一对一关系
     */
    public function user()
    {
        return $this->hasOne('App\Model\User', 'id', 'user_id');
    }

    /**
     * 定义和gallery的一对一关系
     */
    public function gallery()
    {
        return $this->hasOne('App\Model\Gallery', 'id', 'gallery_id');
    }

    /**
     * 定义和点赞记录的关系
     * @return MorphMany
     */
    public function thumbs()
    {
        return $this->morphMany('App\Model\ThumbUp', 'thumbable');
    }

    /**
     * 返回文章关系的query
     * @return Article|Builder
     */
    public function relations()
    {
        $with = [
            'user' => function ($query) {
                $query->select(['id', 'name', 'avatar']);
            },
            'gallery' => function ($query) {
                $query->select('url', 'id');
            },
            'thumbs' => function ($query) {
                $query->select('id', 'thumbable_id')->where('user_id', CustomAuth::id());
            }];

        $query = self::with($with);

        return $query;
    }

    /**
     * 分页获取文章内容
     * @param int $limit // 每页显示的记录数
     * @param string $where 查询条件
     * @param String $orderBy 排序的条件
     * @return
     */
    public function getArticleList($where = null, $orderBy = null)
    {
        // 获取文章的内容
        $articleQuery = $this->relations()->selectRaw('to_base64(id) as id, title, is_origin, user_id, summary, visited, gallery_id, commented, created_at, updated_at, keywords');

        if ($where) {
            $articleQuery = $articleQuery->whereRaw($where);
        }

        switch ($orderBy) {
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
            default:
                $articleQuery = $articleQuery->orderBy('created_at', 'DESC')
                                    ->orderBy('updated_at', 'DESC');
            break;
        }

        $article = \Page::paginate($articleQuery);

        return $article;
    }
}
