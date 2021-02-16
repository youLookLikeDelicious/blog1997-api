<?php
namespace App\Service;

/***********************************************
 * 生成文章的摘要
 ***********************************************/

class SummaryService
{
    /**
     * 生成文章的摘要
     *
     * @param string $content
     * @return string
     */
    public function make (string $content)
    {
        // 获取文章的摘要信息,移除所有标签，只保留pre标签
        preg_match('/<!\-\-\s*more\s*\-\->/', $content, $match);

        if ($match) {
            $morePosition = mb_strpos($content, $match[0]);
            return mb_substr($content, 0, $morePosition);
        }

        // 只保留Pre标签
        $summary = app()->make(FilterStringService::class)->removeHTMLTags($content, 'pre');

        $summary = mb_substr($summary, 0, 450);

        // 如果没有pre的结束标签，自动补全
        if (substr_count($summary, '<pre>') !== substr_count($summary, '</pre>')){
            $summary .= '...</pre>';
        }
        
        return $summary;
    }
}