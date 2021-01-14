<?php
namespace App\Logging;

use Illuminate\Support\Str;
use App\Service\CurlService;
use Monolog\Formatter\NormalizerFormatter;

class LoggerFormat extends NormalizerFormatter
{
    /**
     * 操作类型列表
     * 
     * @var array
     */
    protected $operate_list = [
        'log','update','delete','create','login','logout','register'
    ];

    /**
     * 结果的类型
     *
     * @var array
     */
    protected $result_list = [
        'success','failure','neutral'
    ];

    /**
     * 格式化记录
     *
     * @param array $record
     * @return array
     */
    public function format(array $record)
    {
        $record = parent::format($record);
        
        return $this->formatForEloquent($record);
    }

    /**
     * 格式化Eloquent模型的数据
     *
     * @param array $record
     * @return array
     */
    protected function formatForEloquent (array $record)
    {
        $fields = [];

        $fields['message'] = $record['message'];
        $fields['level'] = Str::lower($record['level_name']);

        // 设置操作的类型
        if (array_key_exists('operate', $record['context']) && in_array($record['context']['operate'], $this->operate_list)) {
            $fields['operate'] = $record['context']['operate'];
        }

        // 设置结果的类型
        if (array_key_exists('result', $record['context']) && in_array($record['context']['result'], $this->result_list)) {
            $fields['result'] = $record['context']['result'];
        }

        // 获取地理位置
        $fields['location'] = $this->getLocationByIp($record['extra']['ip']);

        $fields = array_merge($record['extra'], $fields);

        return $fields;
    }

    /**
     * 通过ip获取地址
     *
     * @param string $ip
     * @return string
     */
    protected function getLocationByIp ($ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP) || !env('GMAP_KEY')) {
            return '未识别的ip';
        }

        $result = CurlService::make('https://restapi.amap.com/v3/ip?ip='. $ip .'&output=json&key=');
        
        $result = json_decode($result);

        if (!is_object($result) || $result->status != 1) {
            return  '定位失败';
        }

        // 获取城市的名称
        $province = $result->province ?: '';

        $city = !is_string($result->city) || $result->province === $result->city
            ? ''
            : $result->city;
            
        return $province . '-' . $city;
    }
}