<?php
namespace App\Service;

class CurlService
{
    /**
     * 执行curl
     *
     * @param string $url
     * @param array $opt
     * @param boolean $getJson 是否返回json结果
     * @return string|array
     */
    public static function make($url, $opt = [], $getJson = false)
    {
        $ch = curl_init($url);

        foreach ($opt as $k => $v) {
            curl_setopt($ch, $k, $v);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        if ($getJson) {
            return json_decode($result, true);
        }
        
        return $result;
    }
}