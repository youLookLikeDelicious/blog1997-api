<?php
namespace App\Service;

class CurlService
{
    /**
     * 执行curl
     *
     * @param string $url
     * @param array $opt
     * @return string
     */
    public static function make($url, $opt = [])
    {
        $ch = curl_init($url);

        foreach ($opt as $k => $v) {
            curl_setopt($ch, $k, $v);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
}