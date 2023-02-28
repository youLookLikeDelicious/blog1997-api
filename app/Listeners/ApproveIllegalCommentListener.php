<?php

namespace App\Listeners;

use App\Models\Comment;
use App\Models\IllegalComment;
use App\Events\ApproveIllegalInfoEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contract\Repository\IllegalComment as RepositoryIllegalComment;

class ApproveIllegalCommentListener
{
    /***
     * @var App\Contract\Repository\IllegalComment
     */
    protected $repository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(RepositoryIllegalComment $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ApproveIllegalInfoEvent $event)
    {
        $mailbox = $event->mailbox;
        
        if ($mailbox->type === 'comment' && $this->repository->isEmpty($mailbox->reported_id)) {

            // 如果是违规的评论，将评论的内容替换
            $comment = Comment::select(['id', 'content'])->find($mailbox->reported_id);

            if (!$comment) return;
    
            // 备份违规的评论
            IllegalComment::create([
                'comment_id' => $comment->id,
                'content' => $comment->content
            ]);
    
            $comment->update([
                'content' => '该评论涉嫌违规,已被删除'
            ]);
    
            $comment->save();
        }

    }
}
