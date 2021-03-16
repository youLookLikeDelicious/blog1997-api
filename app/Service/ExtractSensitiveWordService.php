<?php
namespace App\Service;

use App\Model\SensitiveWord;

class ExtractSensitiveWordService
{
    /**
     * Extract word from file
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function make($request)
    {
        $fileEncode = mb_detect_encoding($request['file']->get(), 'GB2312,GBK,CP936');

        $sensitiveWords = $request['file']->get();

        if ($fileEncode) {
            $sensitiveWords = mb_convert_encoding($sensitiveWords, 'UTF-8', 'GBK');
        }

        // 读取文件中的内容
        $sensitiveWords = $request['file']->get();

        $sensitiveWords = strtr($sensitiveWords, ["\r" => "\n"]);
        $sensitiveWords = explode("\n", $sensitiveWords);

        // 移除重复的值
        $sensitiveWords = array_unique($sensitiveWords);

        // 为记录设置 category
        $result = [];

        foreach($sensitiveWords as $v) {
            // 空白行 或已存在的值,不予以操作
            if (!$v || SensitiveWord::select('id', 'word')->where('word', $v)->first()) {
                continue;
            }

            $result[] = [
                'category_id' => $request['category_id'],
                'word' => $v,
                'created_at' => time(),
                'updated_at' => time()
            ];
        };
        
        return $result;
    }
}