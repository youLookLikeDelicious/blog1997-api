<?php
namespace App\Repository;

use App\Models\IllegalComment;
class IllegalInfo
{
    /**
     * 判断被举报的内容是否已经被处理
     *
     * @param array $data
     * @return boolean
     */
    public function hasBeenProcessed ($data)
    {
        // 被举报的是评论, 文章会被直接删除，违规的评论会被替换
        return IllegalComment::where('comment_id', $data['reported_id'])->first();
    }
}