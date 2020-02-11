<?php

namespace App\Traits\ArticleObserver;

trait deleteImage {
    /**
     * 获取富文本图片的src属性
     * @param $content
     * @return array
     */
    protected function getImgUrl ($content) {
        $pattern = $pattern = "/<img.*?src=\"([\w\.\-\/:]*?)\".*?>/";
        preg_match_all($pattern, $content, $imgUrl, PREG_SET_ORDER);

        return array_column($imgUrl, 1);
    }

    /**
     * 从服务器上将图片删除
     * @param Array $imgList 图片列表
     * @return void
     */
    protected function unlinkImage ($imgList) {
        foreach ($imgList as $v) {
            $filePath = storage_path(strstr($v, 'image'));
            if (is_file($filePath)) {
                unlink($filePath);
            }

            // 删除webp格式的文件
            $filePath = str_replace(strrchr($filePath, '.'), '', $filePath) . '.webp';
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }
    }
}
