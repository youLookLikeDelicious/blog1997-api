<?php

namespace App\Models;

use App\Events\ArticleBackUpDeleted;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// 文章备份表
class ArticleBackUp extends ArticleBase
{
    use HasFactory;

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
        return $this->belongsToMany(Tag::class, 'article_tag', 'article_id');
    }
}
