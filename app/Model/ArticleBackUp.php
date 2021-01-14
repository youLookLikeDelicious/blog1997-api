<?php

namespace App\Model;

use App\Events\ArticleBackUpDeleted;

// 文章备份表
class ArticleBackUp extends ArticleBase
{
    public $dateFormat = 'U';
    protected $table = 'article_back_up';
    protected $guarded = [];
    public $timestamps = true;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'deleted' => ArticleBackUpDeleted::class,
    ];

    /**
     * Define many to many relations with tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('App\Model\Tag', 'article_tag', 'article_id');
    }
}
