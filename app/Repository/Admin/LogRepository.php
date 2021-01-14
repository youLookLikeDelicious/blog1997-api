<?php

namespace App\Repository\Admin;

use App\Model\Log;
use App\Model\User;
use App\Facades\Page;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class LogRepository
{
    /**
     * Log Eloquent
     *
     * @var Log
     */
    protected $log;

    /**
     * User Eloquent
     *
     * @var User
     */
    protected $user;

    /**
     * Create Log repository Instance
     *
     * @param Log $log
     */
    public function __construct(Log $log, User $user)
    {
        $this->log = $log;
        $this->user = $user;
    }

    /**
     * Get log resources
     *
     * @param string $type
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function all(Request $request, $type = 'user')
    {
        $this->validateRequest($request);

        $logQuery = $this->buildQuery($request, $type === 'schedule');

        $logs = Page::paginate($logQuery);

        return $logs;
    }

    /**
     * 构建查询的query
     *
     * @param boolean $isSchedule
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildQuery(Request $request, $isSchedule = false)
    {
        $query = $isSchedule ?
            $this->log->select(['id', 'result', 'time_consuming', 'created_at', 'operate', 'message'])
                ->where('user_id', 0)
            : $this->log->select(['id', 'ip', 'port', 'location', 'result', 'time_consuming', 'user_id', 'created_at', 'operate', 'message'])
                ->with('user:id,name,avatar')
                ->where('user_id', '!=', 0);
        
        // 设置用户信息
        if ($email = $request->input('email')) {
            $query->whereHas('user', function( Builder $query ) use ($email) {
                $query->where('email', $email);
            });
        }

        if ($startDate = $request->input('startDate')) {
            $query->where('created_at', '>=', strtotime($startDate));
        }

        if ($endDate = $request->input('endDate')) {
            $query->where('created_at', '<=', strtotime($endDate));
        }

        $query->orderBy('created_at', 'DESC');

        return $query;
    }

    /**
     * Validate incoming request
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateRequest($request)
    {
        $request->validate([
            'email' => 'sometimes|required|email|exists:user',
            'startDate' => ['nullable', 'regex:/\d{4}-\d{1,2}\d{1,2}/'],
            'endDate' => ['nullable', 'regex:/\d{4}-\d\d?\d\d?/'],
        ]);
    }
}
