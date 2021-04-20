<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FriendLinkRequest extends FormRequest
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
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => __('field.friend link name'),
            'url' => __('field.friend link url')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:21',
            'url'  => 'required|max:120'
        ];
    }
}
