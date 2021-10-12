<?php

namespace App\Contract\Repository;

use App\Model\Comment as Model;

interface Comment {
    /**
     * 获取评论
     * @param int $commentableId
     * @param string $commentableType
     * @return array
     */
    public function getComment ($commentableId, $commentableType);

    /**
     * 获取评论的回复
     */
    public function getReply($rootId, $offset);

    /**
     * 获取博客的留言
     */
    public function getLeaveMessage();

    /**
     * 获取comment的多态关系 commentable_id, commentable_type
     */
    public function getCommentPolymorphById($id);

    /**
     * 获取评论 和回复的数量
     * @param App\Model\Comment $comment
     */
    public function getCommentAndCommentReplyCount($comment);

    /**
     * 获取博客留言的数量
     * 
     * @return int
     */
    public function getLeaveMessageCount();

    /**
     * 获取单个模型
     * 
     * @param int $id
     * @return Model
     */
    public function find($id);

    /**
     * 统计总的评论数量
     *
     * @return integer
     */
    public function totalCommented() : int;

    /**
     * 获取未审核的评论
     *
     * @param \Illuminate\Support\Facades\Request $request;
     * @return \App\Http\Resources\CommonCollection
     */
    public function getUnVerified($request);
}