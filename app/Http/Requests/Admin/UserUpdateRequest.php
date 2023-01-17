<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->id() === $this->user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route()->parameters()['user'] ?? '';

        $rules = [
            'avatar' => 'sometimes|required|image|max:10240', // 文件最大为10M
            'name' => 'sometimes|required|max:45',
            'email' => 'sometimes|required|email|unique:user'
        ];

        if ($user->email !== auth()->user()->email) {
            $rules['email'] += '|unique:user';
        }

        return $rules;
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        $result = $this->all();

        if ($file = $this->file('avatar')) {
            $result['avatar'] = $file;
        }

        return $result;
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated()
    {
        $result =  parent::validated();

        if (! $result) {
            abort(404);
        }

        return $result;
    }
}
