<?php
namespace App\Service;

use Error;

class MapService
{
    /**
     * 申请的API key
     *
     * @var string
     */
  protected $key = '';

  public function __construct()
  {
      $this->key = config('app.gmap_key');

      if (!$this->key) {
          throw new Error('There is no API key');
      }
  }

  /**
   * 根据ip获取地理位置信息
   *
   * @param String $ip
   * @return Json|boolean
   */
  public function getLocationByIp(String $ip)
  {
      if (!$ip) {
          return false;
      }

      $resData =  CurlService::make('https://restapi.amap.com/v3/ip?ip='. $ip .'&output=json&key=' . $this->key);

      $resData = json_decode($resData);

      if (!is_object($resData) || $resData->status != 1) {
          return false;
      }

      return $resData;
  }

  /**
   * 根据经纬度获取位置信息
   *
   * @param string $location
   * @return Json|boolean
   */
  public function decodeLocation(String $location)
  {
      if (!$location) {
          return false;
      }
    $resData =  CurlService::make('https://restapi.amap.com/v3/geocode/regeo?location='. $location .'&output=json&key=' . $this->key);

    $resData = json_decode($resData);

    if (!is_object($resData) || $resData->status != 1) {
        return false;
    }

    return $resData;
  }
}