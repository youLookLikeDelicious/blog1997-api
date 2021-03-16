<?php
namespace App\Service;

use App\Contract\Repository\SensitiveWord;

class SensitiveWordService
{
    protected $wordList;

    public function __construct(SensitiveWord $sensitiveWord)
    {
        $wordList = $sensitiveWord->getWordList();

        $this->wordList = $this->mapWord($wordList);
    }

    /**
     * 将敏感词建成树的结构
     * 
     * @param array $wordList
     * @return array
     */
    protected function mapWord ($wordList) {
        
        $mappedWord = [];
        
        // 遍历敏感词，生成一棵树
        foreach($wordList as $v) {

            // 如果该单词为空，略过
            if (!$v) {
                continue;
            }

            $wordStack = mb_str_split($v);
            
            $tmpMappedWord = &$mappedWord;

            foreach($wordStack as $vv) {
                if (isset($tmpMappedWord[$vv])) {
                    $tmpMappedWord = &$tmpMappedWord[$vv];
                    continue;
                }
                $tmpMappedWord[$vv] = [];
                $tmpMappedWord = &$tmpMappedWord[$vv];
            }
        }

        return $mappedWord;
    }

    /**
     * 过滤敏感词
     * 
     * @param string $str 被过滤的字符串
     * @return string
     */
    public function make (string $str) : string
    {
        // 没有需要过滤的敏感词汇
        if (! $this->wordList || !$str) {
            return $str;
        }

        // 将字符串转成数组
        $strStack = mb_str_split($str);
        
        // 遍历字符串数组
        foreach($strStack as $key => $v) {

            if (!array_key_exists($v, $this->wordList) || !$strStack[$key] || $strStack[$key] === '*') {
                continue;
            }
            
            // 匹配敏感词的分支
            $i = $reservedI = $key;

            $tmpSensitiveWord = $this->wordList[$strStack[$i]];

            while(true) {

                // 敏感词匹配成功
                // 使用屏蔽字符修改文档
                if (!$tmpSensitiveWord) {
                    for ($start = $reservedI + 1; $start < $i; $start++) {
                        $strStack[$start] = '';
                    }
                    $strStack[$reservedI] = '*';
                    $strStack[$i] = '*';
                    break;
                }

                // 判断下一个字符不在map中 并且 map该位置的值不为空 
                // 该情况视为匹配失败，结束循环
                ++$i;

                if (!array_key_exists($strStack[$i], $tmpSensitiveWord)) {
                    break;
                }

                $tmpSensitiveWord  =$tmpSensitiveWord[$strStack[$i]];
            }
        }

        return implode('', $strStack);
    }
}