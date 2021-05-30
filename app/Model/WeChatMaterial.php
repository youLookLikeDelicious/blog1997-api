<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WeChatMaterial extends Model
{
    const IMAGE_TYPE = 1;

    const VOICE_TYPE = 2;

    const VIDEO_TYPE = 3;

    const THUMB_TYPE = 4;

    const ARTICLE_TYPE = 5;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wechat_material';

    /**
     * The timestamp style
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['url', 'media_id', 'type'];
}
