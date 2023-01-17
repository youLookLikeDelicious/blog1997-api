<?php

namespace App\Foundation;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;
use App\Foundation\Traits\CacheModel\CacheComment;
use App\Foundation\Traits\CacheModel\CacheArticle;

/**
 * 缓存站点的一些数据
 */
class CacheModel
{
    use CacheArticle, CacheComment;

    /**
     * 重试的最大延迟 microsecond
     *
     * @var integer
     */
    protected $retryDelay = 200;

    /**
     * 锁的生效时间 seconds
     *
     * @var integer
     */
    protected $ttl = 2;

    /**
     * 获取锁预留的事件百分比
     *
     * @var float
     */
    protected $driftFactory = 0.2;

    /**
     * 缓存相关的数据
     * 
     * @param int $id
     * @param string $type
     * @param string $action
     * @param int $value 缓存的数量
     * 
     * @return int
     */
    protected function cache(string $type, string $action, $id = 0, int $value = 1)
    {
        // 生成缓存的键
        $key = $this->getCacheKey($type, $id);
        // 创建锁
        $lockKey = $key . 'lock';

        if (!$lock = $this->getLock($lockKey)) {
            return;
        }
        
        
        // 如果id不存在，键入该值
        $this->pushCachedIds($type, $id);

        $cachedData = Cache::get($key, []);

        // 该记录存在相关缓存
        $cachedData[$action] = ($cachedData[$action] ?? 0) + $value;

        Cache::forever($key, $cachedData);
        
        // 释放锁
        $lock->release();

        return $cachedData[$action];
    }

    /**
     * 获取缓存的数据
     * 
     * @param int $id
     * @param string $type
     * @param string $action
     * @return int|array
     */
    protected function get($type, $action = '', $id = 0)
    {
        // 生成缓存的键
        $key = $this->getCacheKey($type, $id);

        $cachedData = Cache::get($key, []);

        return $action
            ? $cachedData[$action] ?? 0
            : $cachedData;
    }

    /**
     * 获取缓存的键值
     * @param string $type
     * @param int $id
     *
     * @return void
     */
    protected function getCacheKey($type, $id)
    {
        return "{$type}-{$id}-cache";
    }

    /**
     * 清除一部分缓存
     *
     * @param string $type
     * @param int $id
     * @return boolean
     */
    public function forget($type, $id)
    {
        $cacheKey = $this->getCacheKey($type, $id);

        $lock = $this->getLock($cacheKey . 'lock');

        if (!$lock) {
            return false;
        }

        Cache::forget($cacheKey);

        $lock->release();

        return true;
    }

    /**
     * 获取重试的间隔
     *
     * @return int microseconds
     */
    protected function getRetryDelay()
    {
        return mt_rand(floor($this->retryDelay / 2), $this->retryDelay);
    }

    /**
     * 获取锁 (确实有用)
     * 
     * @return boolean|\Illuminate\Cache\Lock
     */
    public function getLock($key, $retries = 3)
    {   try {
            while ($retries) {
                $startTime = microtime(true);
                $lock = Cache::lock($key, $this->ttl);

                $currentTime = microtime(true);
                $validateTime = $currentTime - $startTime - ($this->ttl * 1000 * $this->driftFactory);

                if (!$lock->get() || $validateTime < 0) {
                    $delay = $this->getRetryDelay();
                    --$retries;
                    usleep($delay);
                }

                return $lock;
            }
        } catch (\Throwable $e) {
            return false;
        }

        return false;
    }

    /**
     * 获取缓存的id列表
     *
     * @param string $type
     * @return array
     */
    public function getCachedIds ($type)
    {
        return Cache::get("{$type}-key-list", []);
    }

    /**
     * 缓存id列表
     *
     * @param string $type
     * @param int $id
     * @return void
     */
    public function pushCachedIds ($type, $id)
    {
        $ids = $this->getCachedIds($type);

        if (in_array($id, $ids)) {
            return;
        }

        array_push($ids, $id);
        Cache::forever("{$type}-key-list", $ids);
    }

    /**
     * 获取系统配置信息
     *
     * @return void
     */
    public function getSystemSetting()
    {
        return cache()->rememberForever('system.setting', function () {
            return SystemSetting::first();
        });
    }

    /**
     * 清除系统配置缓存
     *
     * @return void
     */
    public function forgetSystemSetting()
    {
        cache()->forget('system.setting');
    }
}
