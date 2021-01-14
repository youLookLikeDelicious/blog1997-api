<?php
namespace App\Http\Controllers\Auth\Exception;

use Exception;

class ConnectionException extends Exception
{
    protected $message;
    
    public function __construct($message = '')
    {
        parent::__construct($message);

        $this->message = $message;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->error($this->message);
    }
}