<?php

namespace App\Schedule;

use App\Model\SiteInfo;
use App\Model\ScheduleLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class RedisDataToMysql{

    // 表名对应的代码
    protected $tableCode = [
        'article' => 1,
        'comment' => 2,
        'site_info' => 3
    ];

    /**
     * 转移网站相关的数据
     */
    public function moveSiteDate () {

        $yesterday = $this->getYesterdayDate();

        $visitedKey = 'site-' . $yesterday;

        $scanResult = Redis::hscan($visitedKey, 0)[1];

        $scanResult['record_date'] = strtotime('-1 day');
        $siteInfo = SiteInfo::create($scanResult);

        // 生成日志记录
        $scheduleLog = [
            'action' => 1,
            'operated_table' => $this->tableCode['site_info'],
            'operated_id' => $siteInfo->id,
            'date' => $this->getYesterdayDate(),
            'state' => 'success',
        ];

        // 更新成功
        if ($siteInfo) {

            Redis::del($visitedKey);

            ScheduleLog::create($scheduleLog);
        } else {
            // 更新失败
            $scheduleLog['state'] = 'fail';

            $scheduleLog['message'] = '未知原因';

            ScheduleLog::create($scheduleLog);
        }
    }

    /**
     * 转移文章|评论的 点赞、评论|访问数据
     * @param $table
     * @param $tableCode
     */
    public function moveArticleCommentData ($table) {

        // 转移文章的点赞数
        $cursor = '0';
        $yesterdayDate = $this->getYesterdayDate();
        $pattern = "{$table}-{$yesterdayDate}_*";

        while (1) {
            $scanResult = Redis::scan($cursor, 'MATCH', $pattern);

            $cursor = $scanResult[0];

            $data = $scanResult[1];

            // 遍历昨天相关的数据
            foreach($data as $v) {
                // 获取文章的id
                $articleId = explode('_', $v)[1];

                // 获取文章被缓存的数据
                $articleData = Redis::hscan($v, 0)[1];

                $queryBuilder = DB::table($table)->where('id', $articleId);

                // 生成日志数据
                $scheduleLog = [
                    'action' => 1,
                    'operated_table' => $this->tableCode[$table],
                    'operated_id' => $articleId,
                    'date' => $yesterdayDate,
                    'state' => 'success',
                ];

                try {
                    // 开启mysql和redis的事务
                    DB::beginTransaction();

                    Redis::multi();

                    if (array_key_exists('visited', $articleData) && $articleData['visited']) {
                        $queryBuilder->increment('visited', $articleData['visited']);
                    }

                    if (array_key_exists('liked', $articleData) && $articleData['liked']) {
                        $queryBuilder->increment('liked', $articleData['liked']);
                    }

                    if (array_key_exists('commented', $articleData) && $articleData['commented']) {
                        $queryBuilder->increment('commented', $articleData['commented']);
                    }

                    Redis::del($v);

                    $this->writeLog($scheduleLog);

                    DB::commit();
                    Redis::exec();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Redis::discard();

                    $scheduleLog['state'] = 'fail';
                    $message = $e->getMessage(); // 错误信息
                    $scheduleLog['message'] = strlen($message) > 255 ? substr($e->getMessage(), 0, 255) : $message;

                    $this->writeLog($scheduleLog);
                }
            }

            // 数据读取完成
            if ($cursor == 0) {
                break;
            }
        }
    }

    protected function getYesterdayDate () {
        return date('Y-m-d');
        return date('Y-m-d', strtotime('-1 day'));
    }

    /**
     * 插入log记录
     * @param $data
     */
    protected function writeLog ($data) {
        ScheduleLog::create($data);
    }
    /**
     * 将昨天的redis数据保存到mysql中
     */
    public function __invoke()
    {
        // TODO: Implement __invoke() method.
        // 将昨天缓存的数据转移到数据库中
        $this->moveSiteDate();
        $this->moveArticleCommentData('article');
        $this->moveArticleCommentData('comment');
    }
}
