<?php

namespace App\Http\Requests\Admin;

use App\Rules\Exists;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'authorities.*' => __('field.authorities id'),
            'name' => __('field.name'),
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        $data = parent::validationData();

        $data['remark'] = $data['remark'] ?? '';
        
        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $except = $this->route('role')
            ? $this->route('role')->id
            : '';

        return [
            'name' => 'required|max:45|unique:role,name,' .  $except,
            'remark' => 'present|string|max:450',
            'authorities' => 'nullable|array',
            'authorities.*' => ['integer', new Exists('auth')]
        ];
    }
}
