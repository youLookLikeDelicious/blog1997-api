<?php

namespace App\Repository\Admin;

use App\Http\Resources\CommonCollection;
use App\Model\Log;
use App\Model\User;
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
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function all(Request $request, $type = 'user')
    {
        $this->validateRequest($request);

        $logQuery = $this->buildQuery($request, $type === 'schedule');

        $logs = $logQuery->paginate($request->input('perPage', 10));

        return new CommonCollection($logs);
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
                ->where('user_id', '<>', 0);
        
        // 设置用户信息
        if ($email = $request->input('email')) {
            $query->whereHas('user', function( Builder $query ) use ($email) {
                $query->where('email', $email);
            });
        }

        if ($date = $request->input('date')) {
            $query->whereBetween('created_at', [strtotime($date[0]), strtotime($date[1])]);
        }

        if ($result = $request->input('result')) {
            $query->where('result', $result);
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
            'date' => 'sometimes|required|array',
            'date.*' => 'required|date',
            'result' => 'sometimes|required|in:success,failure,neutral'
        ]);
    }
}
