<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MessageBox extends Model
{    
    protected $table = 'message_box';
    protected $dateFormat = 'U';
    protected $guarded = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['operate_name', 'have_read_name'];

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

    /**
     * 获取操作结果的文本
     *
     * @return string
     */
    public function getOperateNameAttribute()
    {
        if (!$this->operate) return '';

        return [
            'undo'    => '未处理',
            'approve' => '已批准',
            'ignore'  => '已忽略'
        ][$this->operate];
    }

    /**
     * 获取已读状态文本
     *
     * @return string
     */
    public function getHaveReadNameAttribute()
    {
        return $this->have_read === 'yes' ? '已读' : '未读';
    }
}
