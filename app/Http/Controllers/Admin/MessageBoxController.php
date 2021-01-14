<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Events\ApproveIllegalInfoEvent;
use App\Contract\Repository\MessageBox as RepositoryMessageBox;

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
     * 获取举报信息
     * Method GET
     * 
     * @return Response
     */
    public function index(Request $request)
    {
        $result = $this->messageBoxRepository
            ->all($request);

        return response()->success($result);
    }

    /**
     * 批准举报的信息
     * Method POST
     * 
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
     * 忽略举报的信息，将其标记为已读
     * Method POST
     * 
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
     * Get Comment notification
     *
     * @param Request $request
     * @param RepositoryMessageBox $repository
     * @return \Illuminate\Http\Response
     */
    public function getNotification(Request $request, RepositoryMessageBox $repository)
    {
        $notifications = $repository->getNotification($request);
        
        return response()->success($notifications);
    }

    /**
     * Get comment able comments
     *
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
