<?php
namespace App\Service;

use App\Model\Article;
use CURLFile;
use App\Model\WeChatMaterial;

class WeChatService
{
    /**
     * WeChat material model
     *
     * @var WeChatMaterial
     */
    protected $wechatMaterial;

    /**
     * 微信开放平台访问令牌
     *
     * @var string
     */
    protected $token;

    public function __construct(WechatMaterial $wechatMaterial)
    {
        $this->wechatMaterial = $wechatMaterial;
        $this->token = $this->getAccessToken();
    }

    /**
     * 获取请求第三方access TOKEN的地址
     *
     * @return string
     */
    protected function getRequestTokenUrl ()
    {
        // 通过code获取access token
        $api = 'https://api.weixin.qq.com/cgi-bin/token?';
        $api .= 'appid=' . config('app.wechat_public_app_id');
        $api .= '&secret=' . config('app.wechat_public_secret');
        $api .= '&grant_type=client_credential';

        return $api;
    }

    /**
     * 获取微信的授权令牌
     *
     * @return string
     */
    public function getAccessToken()
    {
        return cache()->remember('wechat_public_access_token_' . auth()->id(), 6000, function () {
            $api = $this->getRequestTokenUrl();

            $result = CurlService::make($api, [CURLOPT_CONNECTTIMEOUT => 12], true);

            return !empty($result['errcode'])
                ? ''
                : $result['access_token'];
        });
    }

    /**
     * 上传图文素材的封面
     *
     * @param string $filePath
     * @return WeChatMaterial | boolean
     */
    public function uploadMaterial($filePath, $type = 'thumb')
    {
        $api = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$this->token&type=$type";

        $curlFile = new CURLFile($filePath, mime_content_type($filePath));

        $mediaData = ['media' => $curlFile];

        $options = [
            CURLOPT_POST => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $mediaData
        ];

        $response = CurlService::make($api, $options, true);

        $data = [
            'media_id' => $response['media_id'] ?? ''
        ];

        $wechatMaterial = $this->wechatMaterial->create($data);

        return $wechatMaterial;
    }

    /**
     * 获取素材状态码
     *
     * @param string $type
     * @return int
     */
    protected function getTypeCode($type)
    {
        switch ($type) {
            case 'image':
                return 1;
            case 'voice':
                return 2;
            case 'video':
                return 3;
            case 'thumb':
                return 4;
            case 'article':
                return 5;
            default:
                return 0;
        }
    }

    /**
     * 新增永久图文素材
     *
     * @param Article $article
     * @return void
     */
    public function uploadNewsMaterial(Article $article)
    {
        // 首先上传封面素材
        $url = ltrim($article->gallery->url, '/');
        // $url = preg_replace('/(\.jpg)$/', '.min$1', $url);
        $galleryUrl = storage_path(ltrim($url, '/'));

        $material = $this->uploadMaterial($galleryUrl);

        if (!$material) {
            return;
        }

        $api = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=$this->token";

        $articles = [
            'articles' => [[
                'title' => $article->title,
                'thumb_media_id' => $material->media_id,
                'author' => $article->user->name,
                "digest"=> '', //文章摘要
                'show_cover_pic' => 1,
                'content'=> $article->content,
                'content_source_url' => rtrim(config('app.url'), '/') . "/article/$article->identity" ,
                'need_open_comment' => 0,
                'only_fans_can_comment' => 0
            ]]
        ];

        $response = CurlService::make($api, [
            CURLOPT_POST => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => json_encode($articles, JSON_UNESCAPED_UNICODE)
        ], true);

        $this->wechatMaterial->create([
            'media_id' => $response['media_id']
        ]);
        dd($response);
    }
}