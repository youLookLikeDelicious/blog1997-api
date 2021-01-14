<?php
namespace App\Service;

use Illuminate\Support\Facades\Storage;

class FilterStringService
{
    /**
     * remove xss
     * 
     * @param string $str
     * @return string
     */
    public function removeXss ($str)
    {
        require_once app_path('Library/HTMLPurifier.auto.php');

        $_clean_xss_config = \HTMLPurifier_Config::createDefault();

        $_clean_xss_config->set('Core.Encoding', 'UTF-8');

        // 设置保留的标签
        $_clean_xss_config->set('HTML.Allowed','div[style],b,strong,i,em,ul,ol,li,p[style],br,span[style|class],img[src|width|height|style],code,pre');

        $_clean_xss_config->set('CSS.AllowedProperties', 'font,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align,width,height');

        $_clean_xss_config->set('HTML.TargetBlank', TRUE);

        $_clean_xss_obj = new \HTMLPurifier($_clean_xss_config);

        // 执行过滤
        return $_clean_xss_obj->purify($str);
    }


    /**
     * 移除所有的HTML标签
     * 
     * @param string $str 
     * @param string $tag 要保留的标签
     * @return string
     */
    public function removeHTMLTags (string $str, $tag = '') : string
    {
        if ($tag) {
            $pattern = "/<(?!{$tag}|\/{$tag})[^>]*>/";
        } else {
            $pattern = '/<[^>]+>/';
        }
        
        $str =  preg_replace($pattern, '', $str);

        return $str;
    }

    /**
     * 提取字符串 img标签素有的url (只提取本地的图片地址)
     *
     * @param string $content
     * @return array
     */
    public function extractImagUrl ($content)
    {
        $pattern = $pattern = "/<img.*?data-src=\"([^\"]*)\".*?>/";
        preg_match_all($pattern, $content, $imgUrl, PREG_SET_ORDER);

        $urls =  array_column($imgUrl, 1);
        
        if (! $urls) {
            return [];
        }

        // 过滤 查找出本地的图片
        $result = array_filter($urls, function ($url) {
            // remove image url query parameters
            $pureUrl = str_replace(strrchr($url, '?'), '', $url);

            return Storage::exists($pureUrl);;
        });

        return array_values($result);
    }

    /**
     * 将 <img />元素的src属性转换为data-src
     *
     * @param string $content
     * @return string
     */
    public function coverImageSrc($content)
    {
        return preg_replace('/((?<=<img).*?)\s+\bsrc=([^>]+>)/im', '$1 class="lazy" data-src=$2', $content);
    }
}