<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Events\ApproveIllegalInfoEvent;
use App\Contract\Repository\MessageBox as RepositoryMessageBox;

/**
 * @group Message management
 * 
 * 管理后台的消息
 * 包括通知信息、用户举报信息等相关的数据
 */
class MessageBoxController extends Controller
{
    /**
     * 获取消息的仓库
     *
     * @var App\Contract\Repository\MessageBox
     */
    protected $messageBoxRepository;

    public function __construct(RepositoryMessageBox $repository)
    {
        $this->messageBoxRepository = $repository;
    }
    /**
     * Get reported illegal records
     * 
     * 获取举报信息
     * 
     * @queryParam have_read 消息是否已读,例如:yes,no
     * @responseFile response/admin/message-box/index.json
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $result = $this->messageBoxRepository
            ->all($request);

        return $result;
    }

    /**
     * Approve illegal information
     * 
     * 批准举报的信息,同时会删除对应的内容
     * 
     * @urlParam id 举报记录的ID
     * @responseFile response/admin/message-box/approve.json
     * @param int $id
     * @return Response
     */
    public function approve($id)
    {
        $mailbox = $this->messageBoxRepository->find($id);

        DB::transaction(function () use ($mailbox) {
            $mailbox->update([
                'have_read' => 'yes',
                'operate' => 'approve'
            ]);
            event(new ApproveIllegalInfoEvent($mailbox));
            
        }); 

        return response()->success('', '审批成功');
    }

    /**
     * Ignore reported illegal info
     * 
     * 忽略举报的信息,会自动将该记录标记为已读
     * 
     * @urlParam id 举报记录的ID
     * @responseFile response/admin/message-box/ignore.json
     * @param int $id
     * @return Response
     */
    public function ignore($id)
    {
        $record = $this->messageBoxRepository->find($id);

        DB::transaction(function () use ($record) {
            $record->update([
                'have_read' => 'yes',
                'operate' => 'ignore'
            ]);
        });

        return response()->success('', '处理成功');
    }

    /**
     * Get notification
     * 
     * 获取通知的内容
     * Get Comment notification
     *
     * @queryParam have_read 是否已读
     * @responseFile response/admin/message-box/get-notification.json
     * @param Request $request
     * @param RepositoryMessageBox $repository
     * @return \Illuminate\Http\Response
     */
    public function getNotification(Request $request, RepositoryMessageBox $repository)
    {
        $notifications = $repository->getNotification($request);
        
        return $notifications->toResponse($request);
    }

    /**
     * Get more comments about specific notification
     *
     * 获取通知相关的评论
     * 
     * @urlParam  id  通知的ID
     * @queryParam p  请求的页数
     * @responseFile response/admin/message-box/get-commentable-comments.json
     * @param RepositoryMessageBox $repository
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getCommentAbleComments(RepositoryMessageBox $repository, $id)
    {
        $notification = $repository->find($id);

        $comments = $repository->getCommentAbleComments($notification);

        return response()->success($comments);
    }
}
