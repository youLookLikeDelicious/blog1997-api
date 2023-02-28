<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
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
            'files.*' => 'required|image|max:10240', // 文件最大为10M
            'id'      => 'nullable',
            'album'   => 'sometimes|max:45'
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        $files = $this->file('upfile');

        return array_merge([
            'files' => is_array($files) ? $files : [$files]
        ], $this->all());
    }
}
