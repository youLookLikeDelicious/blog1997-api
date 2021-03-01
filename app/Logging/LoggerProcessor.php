<?php
namespace App\Logging;

use Illuminate\Support\Facades\Auth;

class LoggerProcessor
{
    /**
     * invoke Logger processor
     *
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $request = request();
        $record['extra'] = [
            'created_at' => time(),
            'user_id' => Auth::id() ?? 0,
            'ip' => $_SERVER["HTTP_CF_CONNECTING_IP"] ?? $request->ip(),
            'port' => $request->server('REMOTE_POST') ?: '',
            'origin' => $request->headers->get('origin') ?: '',
            'user_agent' => $request->server('HTTP_USER_AGENT') ?: '',
            'request_url' => $request->fullUrl() ?: '',
            'time_consuming' => isset($GLOBALS['startTime']) ? ceil((microtime(true) - $GLOBALS['startTime'])* 1000)  : 0
        ];
        
        return $record;
    }
}