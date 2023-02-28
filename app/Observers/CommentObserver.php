<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\Comment;
use App\Facades\CacheModel;
use App\Events\NotifyCommentEvent;
use Illuminate\Support\Facades\Log;
use App\Contract\Repository\Comment as CommentRepository;
use App\Models\MessageBox;

class CommentObserver
{
    /**
     * 获取评论数据
     *
     * @var \App\Contract\Repository\Comment
     */
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Handle the comment "creating" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function creating(Comment $comment)
    {
        if ($this->commentNeedVerify()) {
            $comment->verified = 'no';
        }

        if ($comment->able_type === 'Blog1997' && $comment->able_id) {
            $comment->able_id = 0;
        }

        // 如果评论的是文章，设置article_id 字段
        if ($comment->able_type === 'article') {
            $comment->article_id = $comment->able_id;
        } else if ($comment->able_id) {
            // 如果评论的使文章， 获取文章的id 
            // 用于删除
            $parentComment = $this->commentRepository->find($comment->root_id);
            if ($parentComment->able_type === 'article') {
                $comment->article_id = $parentComment->able_id;
            }
        }
    }

    /**
     * Handle the comment "created" event.
     *
     * @param Comment $comment
     * @param bool $logging 是否写入日志
     * @return bool
     */
    public function created(Comment $comment, $logging = true)
    {
        if ($logging) {
            Log::info('评论成功', ['operate' => 'create', 'result' => 'success']);
        }

        if ($comment->verified === 'no') {
            return;
        }

        $this->makeNotification($comment);
    }

    /**
     * Handle the comment "updated" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        if ($this->commentNeedVerify() && $comment->isDirty('verified') && $comment->verified === 'yes') {
            $this->makeNotification($comment);
        }
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param Comment $comment
     * @return bool
     */
    public function deleting(Comment $comment)
    {
        $comment->deleteCommentedNum = $this->commentRepository->getCommentAndCommentReplyCount($comment);

        $cometNeedVerify = $this->commentNeedVerify();

        if (( $cometNeedVerify && $comment->verified === 'yes') || !$this->commentNeedVerify()) {
            switch ($comment->able_type) {
                case 'article':
                    // 删除文章评论
                    CacheModel::decrementArticleCommented($comment->able_id, $comment->deleteCommentedNum);
                    Comment::where('root_id', $comment->id)
                        ->delete();
                    break;
                case 'comment':
                    // 删除评论的回复
                    CacheModel::decrementCommentCommented($comment->able_id, $comment->deleteCommentedNum);
                    
                    if ($comment->level == 3) {
                        CacheModel::decrementCommentCommented($comment->root_id, $comment->deleteCommentedNum);
                        $rootComment = Comment::withTrashed()->find($comment->root_id);

                        if ($rootComment->able_type === 'article') {
                            CacheModel::decrementArticleCommented($rootComment->able_id, $comment->deleteCommentedNum);
                        } else {
                            CacheModel::decrementLeaveMessageCommented($comment->deleteCommentedNum);
                        }
                    }

                    Comment::where('able_id', $comment->id)->delete();
                    break;
                case 'Blog1997'::class:
                    // 删除留言
                    CacheModel::decrementLeaveMessageCommented($comment->deleteCommentedNum);
                    Comment::where('root_id', $comment->id)
                        ->delete();
                    break;
            }
        }

        $this->destroyNotification($comment);

        Log::info('评论删除成功', ['operate' => 'delete', 'result' => 'success']);
    }

    /**
     * 判断当前系统是否设置评论需要审核
     *
     * @return boolean
     */
    protected function commentNeedVerify()
    {
        $config = CacheModel::getSystemSetting();

        return $config && $config['verify_comment'] === 'yes';
    }

    /**
     * Destroy related notification in the site
     *
     * @param Comment $comment
     * @return void
     */
    protected function destroyNotification(Comment $comment)
    {        
        // MessageBox::where('type', 'App\Models\Comment')
        //     ->where('sender', $comment->user_id)
        //     ->where('reported_id', $comment->id)
        //     ->delete();
        MessageBox::whereHasMorph(
            'notificationable',
            ['comment'],
            fn ($q) => $q->where('reported_id', $comment->id)->where('sender', $comment->user_id)
        )->delete();
    }

    /**
     * 生成相关的 站内 通知
     *
     * @param Comment $comment
     * @return void
     */
    protected function makeNotification(Comment $comment)
    {
        switch ($comment->able_type) {
            case 'article':
                CacheModel::incrementArticleCommented($comment->able_id);
                break;
            case 'comment':
                CacheModel::incrementCommentCommented($comment->able_id);

                // 如果是三级评论，顶级平评论数 + 1
                if ($comment->level == 3) {
                    CacheModel::incrementCommentCommented($comment->root_id);
                }

                $rootComment = Comment::find($comment->root_id);

                // 防止在被处理前，评论被删除的情况
                if ($rootComment) {
                    $this->created($rootComment, false);
                }

                break;
        }

        event(new NotifyCommentEvent($comment));
    }
}
