<?php

namespace App\Listeners;

use App\Model\Article;
use App\Model\Comment;
use App\Model\ThumbUp;
use App\Model\MessageBox;
use App\Events\NotifyCommentEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contract\Repository\Comment as RepositoryComment;
use App\Contract\Repository\User;

class NotifyCommentListener implements ShouldQueue
{
    /**
     * Comment Repository instance
     *
     * @var RepositoryComment
     */
    protected $commentRepository;

    /**
     * User repository instance
     *
     * @var User
     */
    protected $userRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(RepositoryComment $comment, User $userRepository)
    {
        $this->commentRepository = $comment;
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NotifyCommentEvent $event)
    {
        $comment = $event->comment;
        // 获取通知的内容
        $content = $this->getNotificationContent($comment);
        // 获取通知的接收者
        $receivers = $this->getReceiver($comment);
        // 获取根评论的id
        $rootCommentId = $comment->getRootCommentId();

        // 评论通知的 标识符是一级评论的id和sender、type、receiver
        foreach($receivers as $receiver) {
            $message = MessageBox::firstOrCreate([
                'receiver' => $receiver,
                'type' => 'App\Model\Comment',
                'sender' => $comment->user_id,
                'root_comment_id' => $rootCommentId
            ]);

            if (! $message->content) {
                $message->reported_id = $comment->id;
                $message->content = $content;
            } else {
                $message->have_read = 'no';
            }

            $message->save();
        }
    }

    /**
     * Get receiver of notification
     *
     * @param \App\Model\Comment $comment
     * @return array
     */
    protected function getReceiver($comment)
    {
        $userIds = [$comment->reply_to];

        $rootComment = Comment::select('id', 'able_id', 'user_id', 'reply_to')
            ->find($comment->root_id);

        // 如果是二级评论和三级评论
        // 通知一级评论 被评论的用户
        if ($comment->level >= 2) {
            array_push($userIds, $rootComment->reply_to);
        }

        // 如果是三级评论
        // 通知一级评论的 发起者
        if ($comment->level === 3) {
            array_push($userIds, $rootComment->user_id);
        }

        $excludeIds = [$comment->user_id];
        
        $userIds = array_diff(array_unique($userIds), $excludeIds);

        return $userIds;
    }

    /**
     * Generate notification content
     *
     * @param Comment $comment
     * @return string
     */
    protected function getNotificationContent($comment)
    {
        switch ($comment->able_type) {
            case 'Blog1997':
                return '留下了一些足迹';
            case Comment::class:
                return '回复了相关内容';
            case Article::class:
                return '评论了您的文章';
            case ThumbUp::class :
                return '点了个赞';
        }
    }
}
