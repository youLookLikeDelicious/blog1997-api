<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginByProviderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'nullable|in:wechat,github,qq,weixinMini'
        ];
    }

    /**
     * Get validated data
     *
     * @return array
     */
    public function validated()
    {
        $data = parent::validated();

        if (!$data['type']) {
            $data['type'] = 'github';
        }

        return $data;
    }
}
