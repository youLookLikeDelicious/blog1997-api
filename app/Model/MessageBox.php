<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MessageBox extends Model
{    
    protected $table = 'message_box';
    protected $dateFormat = 'U';
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
    ];
    
    /**
     * Define relations with user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'sender', 'id')->withDefault([
            'name' => '该用户已注销'
        ]);
    }

    /**
     * Define morph one to one with article and comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function notificationable()
    {
        return $this->morphTo('notificationable', 'type', 'reported_id')
            ->withDefault([
                'content' => '该内容已被删除'
            ]);
    }

    /**
     * Define morph one to one with article and comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function thumbs()
    {
        return $this->belongsTo('App\Model\ThumbUp', 'reported_id');
    }
}
