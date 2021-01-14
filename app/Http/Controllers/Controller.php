<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 执行事务，是否涉及到redis
     *
     * @var boolean
     */
    protected $withRedis = true;


    /**
     * 手动指定的缓存
     *
     * @var string
     */
    protected $cacheDriver = '';

    public function __construct()
    {
        $this->cacheDriver = env('CACHE_DRIVER');
    }
    /**
     * 数据库开始事务
     *
     * @return void
     */
    public function beginTransition ($withRedis = true)
    {
        if ($this->withRedis !== $withRedis) {
            $this->withRedis = $withRedis;
        }

        if ($this->isUsingRedisCacheDriver() && $this->withRedis) {
            Redis::multi();
        }

        DB::beginTransaction();
    }

    /**
     * 数据库回滚事务
     * 
     * @return void
     */
    public function rollBack ()
    {
        if ($this->isUsingRedisCacheDriver() && $this->withRedis) {
            Redis::discard();
        }

        DB::rollBack();
    }

    /**
     * 数据库提交事务
     *
     * @return void
     */
    public function commit ()
    {
        if ($this->isUsingRedisCacheDriver() && $this->withRedis) {
            Redis::exec();
        }

        DB::commit();
    }

    /**
     * 判断是否使用redis缓存
     *
     * @return boolean
     */
    protected function isUsingRedisCacheDriver ()
    {
        return $this->cacheDriver === 'redis';
    }

    /**
     * 手动指定缓存
     *
     * @param string $driver
     * @return void
     */
    public function setCacheDriver($driver)
    {
        $this->cacheDriver = $driver;
    }
}
