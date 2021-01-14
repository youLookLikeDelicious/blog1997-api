<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $dateFormat = 'U';
    
    protected $table = 'comment';

    protected $guarded = [];

    protected $appends = [
        'thumbs'
    ];

    /**
     * 获取点赞的次数
     * @return MorphOne
     */
    public function getThumbsAttribute()
    {
        $thumb =  ThumbUp::select('id')
            ->where('able_id', $this->id)
            ->where('able_type', self::class);
        
        if (Auth::id()) {
            $thumb = $thumb->where('user_id', Auth::id());
        }

        $thumb->get();

        return $thumb->count();
    }

    /**
     * define comment belongs to user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)
            ->withDefault([
                'name' => '该用户已注销',
                'avatar' => ''
            ]);
    }

    /**
     * Define relations with receiver
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function receiver()
    {
        return $this->hasOne(User::class, 'id', 'reply_to')
            ->withDefault([
                'name' => '该用户已注销',
                'avatar' => ''
            ]);
    }

    /**
     * Define relation with article
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo('commentable', 'able_type', 'able_id');
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
     * Get root comment id
     *
     * @return int
     */
    public function getRootCommentId()
    {
        return $this->level === 1
            ? $this->id
            : $this->root_id;
    }
}
