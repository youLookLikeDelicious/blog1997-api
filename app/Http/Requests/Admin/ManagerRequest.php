<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ManagerRequest extends FormRequest
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
        $rules = [
            'email' => 'required|unique:user',
            'roles' => 'sometimes|array',
            'roles.*' => 'required|integer|min:1'
        ];

        if ($this->isMethod('put')) {
            unset($rules['email']);
        }

        return $rules;
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated()
    {
        $result = parent::validated();        

        return $this->isMethod('post')
            ? [
                'email' => $result['email'],
                'roles' => $result['roles'] ?? []
            ]
            : [
                'roles' => $result['roles'] ?? []
            ];
    }
}
