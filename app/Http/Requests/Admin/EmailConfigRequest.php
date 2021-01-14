<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EmailConfigRequest extends FormRequest
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
            'driver' => 'required|max:45',
            'email_server' => 'required|max:120',
            'port' => 'required|integer',
            'email_addr' => 'required|max:120',
            'encryption' => 'required|in:none,ssl,tls',
            'sender' => 'required|max:45',
            'password' => 'sometimes|required|max:120'
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        $data = $this->all();

        if (isset($data['password']) && $data['password'] === 'blog1997') {
            unset($data['password']);
        }

        return $data;
    }
}
