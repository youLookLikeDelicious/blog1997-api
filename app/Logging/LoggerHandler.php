<?php
namespace App\Logging;

use App\Events\LogEvent;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
class LoggerHandler extends AbstractProcessingHandler
{
    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record) : void
    {
        event(new LogEvent($record['formatted']));
    }

    /**
     * 获取默认的Formatter
     * 
     * @return LoggerFormat
     */
    protected function getDefaultFormatter() : FormatterInterface
    {
        return new LoggerFormat();
    }


}