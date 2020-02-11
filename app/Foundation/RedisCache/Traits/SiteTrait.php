<?php
namespace App\Foundation\RedisCache\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

trait SiteTrait {
    /**
     * 网站当天点击的总次数
     */
    public function incrVisited () {
        // Redis::hincrby("site-{$this->date}", 'visited', 1);
        // 记录总的访问次数
        Redis::hincrby('site-info', 'visited', 1);
    }

    /**
     * 获取网站中文章的总数量
     * @return mixed $total
     */
    public function getArticleNum () {

        $total = Redis::get('article-quantity');

        if ($total === null) {
            $total = DB::table('article')->selectRaw('count(id) as count')->first()->count;
            Redis::set('article-quantity', $total);
        }

        return $total + 0;
    }

    /**
     * 增加网站留言的数量
     */
    public function incrSiteCommented () {
        Redis::hincrby('site-info', 'commented', 1);
    }
    /**
     * 减少网站留言的次数
     * @param $num 减少的次数，默认是1
     */
    public function decrSiteCommented ($num = 1) {
        Redis::hincrby('site-info', 'commented', -$num);
    }

    /**
     * 获取网站留言的数量
     * @param int 
     */
    public function getSiteLeaveMessageNum () {
        return Redis::hget('site-info', 'commented') + 0;
    }
}
