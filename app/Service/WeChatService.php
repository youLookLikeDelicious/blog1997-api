<?php
namespace App\Service;

use App\Facades\HighLightHtml;
use App\Models\Article;
use CURLFile;
use App\Models\WeChatMaterial;
use Exception;
use Parsedown;
use Symfony\Component\HttpFoundation\File\Exception\NoFileException;

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
        $key = 'wechat_public_access_token_' . auth()->id();

        $token = cache()->remember($key, 6000, function () {
            $api = $this->getRequestTokenUrl();

            $result = CurlService::make($api, [CURLOPT_CONNECTTIMEOUT => 12], true);

            if (!empty($result['errmsg'])) {
                throw new WechatMaterialUploadException($result['errmsg']);
            }

            return $result['access_token'];
        });

        if (!$token) {
            cache()->forget($key);
        }

        return $token;
    }

    /**
     * 上传图文素材的封面
     *
     * @param string $filePath 在线资源url|本地资源的绝对路径
     * @return WeChatMaterial | boolean
     */
    public function uploadMaterial($filePath, $type = 'thumb')
    {
        $api = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$this->token&type=$type";

        if (strpos($filePath, '?')) {
            $filePath = strstr($filePath, '?', true);
        }

        if (str_starts_with($filePath, '/')) {
            $filePath = storage_path(ltrim($filePath, '/'));
        }

        // 如果资源不是本地的,创建一个临时文件
        if (!is_file($filePath)) {
            $url = $filePath;
            $filePath = tempnam(sys_get_temp_dir(), 'XY');

            if (!$filePath) {
                throw new NoFileException('未识别的资源URL');
            }

            file_put_contents($filePath, file_get_contents($url));

            $curlFile = new CURLFile($filePath);
        }

        $curlFile = new CURLFile($filePath);

        // 定义文件的mine type
        if (!$curlFile->getMimeType()) {
            $curlFile->setMimeType($this->getMineType($filePath));
            $fileName = $curlFile->getFilename();
            $fileName = substr(strrchr($filePath, '/'), 1) . '.jpg';
            $curlFile->setPostFilename($fileName);
        }

        $mediaData = ['media' => $curlFile];

        $response = $this->sendPostRequest($api, [CURLOPT_POSTFIELDS => $mediaData]);
        if (!empty($url)) {
            unlink($filePath);
        }

        $data = [
            'media_id' => $response['media_id'] ?? '',
            'url' => $response['url'] ?? '',
            'type' => $this->getTypeCode($type)
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
        $content = $article->is_markdown === 'yes'
            ? (new Parsedown())->text($article->content)
            : $article->content;

        // 首先上传封面素材
        // $url = preg_replace('/(\.jpg)$/', '$1', $article->gallery->url);

        // 获取文章中的图片
        preg_match_all('/<img.*src="([^"]+)"/', $content, $matches);

        // 上传图片素材
        foreach($matches[1] as $image) {
            $material = $this->uploadMaterial($image, 'image');
            $content = str_replace($image, $material->url, $content);
        }

        $content = HighLightHtml::make($article->content);

        $material = $this->uploadMaterial($article->gallery->url);

        $api = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=$this->token";

        $articles = [
            'articles' => [[
                'title' => $article->title,
                'thumb_media_id' => $material->media_id,
                'author' => $article->user->name,
                "digest"=> '', //文章摘要
                'show_cover_pic' => 1,
                'content'=> $content,
                'content_source_url' => rtrim(config('app.url'), '/') . "/article/$article->identity" ,
                'need_open_comment' => 0,
                'only_fans_can_comment' => 0
            ]]
        ];

        $response = $this->sendPostRequest($api, [CURLOPT_POSTFIELDS => json_encode($articles, JSON_UNESCAPED_UNICODE)]);

        if (!empty($response['errmsg'])) {
            throw new WechatMaterialUploadException('素材上传失败,' . $response['errmsg']);
        }

        $this->wechatMaterial->create([
            'media_id' => $response['media_id']
        ]);
    }

    /**
     * 向微信接口发送api请求
     *
     * @param string $api
     * @param array $options
     * @return array
     * @throws WechatMaterialUploadException
     */
    protected function sendPostRequest($api, $options = [])
    {
        $options = [
            CURLOPT_POST => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false
        ] + $options;

        $response = CurlService::make($api, $options, true);

        if (!empty($response['errmsg'])) {
            throw new WechatMaterialUploadException('素材上传失败,' . $response['errcode'] . ':' . $response['errmsg']);
        }

        return $response;
    }


    /**
     * 获取文件的Mine type
     *
     * @param string $filePath
     * @return string
     */
    protected function getMineType($filePath)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $mineType = finfo_file($finfo, $filePath);

        finfo_close($finfo);

        return $mineType;
    }
}