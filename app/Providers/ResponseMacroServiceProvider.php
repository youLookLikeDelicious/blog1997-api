<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // 成功的response
        Response::macro('success', function ($data = '', $message = 'success') {
            return response([
                'status'  => 'success',
                'message' => $message,
                'data'    => $data
            ]);
        });
        // 失败的response
        Response::macro('error', function ($message = 'error') {
            // 处理validator的messageBag对象
            if (is_object($message)) {
                $message = implode("\n", $message->all());
            }
            return response([
                'status'  => 'error',
                'message' => $message
            ], 400);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
