<?php

namespace App\Foundation;

use App\Foundation\RedisCache\Traits\SiteTrait;
use App\Foundation\RedisCache\Traits\ArticleTrait;
use App\Foundation\RedisCache\Traits\CommentTrait;
/**
 * Class RedisCache
 * @package App\Foundation
 * 集中管理redis数据
 */

class RedisCache{

    use ArticleTrait, SiteTrait, CommentTrait;

    protected $date = ''; // 今天的日期
    protected $yesterdayDate = '';

    public function __construct() {
        $this->date = date('Y-m-d');
        $this->yesterdayDate = date('Y-m-d', strtotime('-1 day'));
    }
}
