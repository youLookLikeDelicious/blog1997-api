<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Article extends ArticleBase
{
    //
    public $dateFormat = 'U';

    protected $table = 'article';

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'topic_id' => 'int'
    ];

    /**
     * 定义和作者的一对一关系
     */
    public function author()
    {
        return $this->hasOne('App\Model\User', 'id', 'user_id')->withDefault([
            'name' => '该用户已注销',
            'avatar' => ''
        ]);
    }

    /**
     * 定义和作者的一对一关系
     */
    public function user()
    {
        return $this->author();
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
        return $this->morphMany('App\Model\ThumbUp', 'thumbable', 'able_type', 'able_id');
    }

    /**
     * 定义和专题的一对多关系
     * @return MorphMany
     */
    public function topic()
    {
        return $this->belongsTo('App\Model\Topic', 'topic_id');
    }

    /**
     * 返回文章关系的query
     * @return Article|Builder
     */
    public function withAuthorAndGalleryAndThumbs()
    {
        $with = ['author:id,name,avatar', 'gallery:id,url,thumbnail'];

        if (Auth::id()) {
            $with['thumbs'] = function ($query) {
                $query->select('id', 'able_id')->where('user_id', Auth::id());
            };
        }

        $query = self::with($with);

        return $query;
    }

    /**
     * Define many to many relations with tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('App\Model\Tag', 'article_tag');
    }

    /**
     * eager load author
     * 
     * @return Builder
     */
    public function withAuthorAndGallery()
    {
        return $this->with([
            'author' => function ($query) {
                $query->select(['id', 'name', 'avatar']);
            },
            'gallery' => function ($query) {
                $query->select(['id', 'url', 'thumbnail']);
            }
        ]);
    }

    /**
     * Define relation with comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->morphMany('App\Model\Comment', 'able');
    }

    /**
     * Scope a query to draft article.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query, $articleId)
    {
        return $query->where('is_draft', 'yes')
            ->where('user_id', auth()->id())
            ->where('article_id', $articleId);
    }

    /**
     * Check current article is draft
     *
     * @return boolean
     */
    public function isDraft()
    {
        return $this->is_draft === 'yes';
    }

    public function getIdentityAttribute()
    {
        return base64_encode($this->id);
    }
}
