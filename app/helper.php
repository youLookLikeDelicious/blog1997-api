<?php
// 过滤富文本
// if(!function_exists('removeXSS')){
// }
if (!function_exists('getCacheSeconds')) {
    function getCacheSeconds($seconds) {
        return app()->environment() === 'production' ? $seconds : 0;
    }
}

if (!function_exists('allowSendEmail')) {
    /**
     * 判断是否配置邮箱
     * 
     * @return bool
     */
    function allowSendEmail() {
        return config('mail.username') && config('mail.password') && config('mail.host');
    }
}
