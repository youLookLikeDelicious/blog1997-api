<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    
    public $dateFormat = 'U';
    
    protected $table = 'comment';

    protected $guarded = ['created_at', 'updated_at', 'id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
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
        return $this->morphMany('App\Models\Comment', 'able');
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'root_id', 'id')->with('user:id,name,avatar');
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
