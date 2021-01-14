<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'name' => 'required|string|max:15',
            'parent_id' => 'required|integer|min:0',
            'route_name' => 'nullable|max:45'
        ];
    }

    /**
     * Auth parent id should not equal to auth id
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->auth && $this->auth->id === $this->parent_id) {
                $validator->errors()->add('field', '无效的父级权限!');
            }
        });
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated()
    {
        $result = parent::validated();

        if (empty($result['route_name'])) {
            $result['route_name'] = '';
        }

        return $result;
    }
}
