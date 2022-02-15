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
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => __('field.name'),
            'parent_id' => __('field.parent_id'),
            'route_name' => __('field.route_name')
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
            'name' => 'required|string|max:15',
            'parent_id' => 'sometimes|required|integer|min:0',
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

        if (empty($result['parent_id'])) {
            $result['parent_id'] = 0;
        }
        
        return $result;
    }
}
