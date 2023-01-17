<?php
namespace App\Repository\Admin;

use App\Models\EmailConfig as Model;
use Illuminate\Support\Facades\Cache;

class EmailConfig
{
    /**
     * Get email config detail
     *
     * @return Model
     */
    public function get()
    {
        $result = Cache::rememberForever('email-config', function () {
            return Model::select(['id', 'driver', 'email_server', 'email_addr', 'port', 'encryption', 'sender', 'password'])
                ->first();
        });

        if (! $result) {
            Cache::forget('email-config');
        }

        return $result;
    }
}