<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ImageSource implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // 获取所有的图片地址
        preg_match_all('/(?<=<img).+?src="([^>]+)"[^>]*?>/', $value, $matches, PREG_PATTERN_ORDER);

        if (!empty($matches[1])) {
            foreach($matches[1] as $v) {
                if (!preg_match('/https?\:\/\/img.baidu\.com/', $v)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.image_source');
    }
}
