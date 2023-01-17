<?php
namespace App\Logging;

use Monolog\Logger as MonoLogger;

class Logger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $log = new MonoLogger('custom');

        $log->pushHandler(new LoggerHandler);
        $log->pushProcessor(new LoggerProcessor);
        
        return $log;
    }
}