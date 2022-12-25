<?php

namespace App\Http\Requests;

use App\Facades\Upload;
use Illuminate\Foundation\Http\FormRequest;

class ManualRequest extends FormRequest
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
            'name'      => 'required|max:45',
            'summary'   => 'sometimes|max:255',
            'is_public' => 'sometimes|in:1,2',
            'cover'     => 'required|string|max:120'
        ];
        
        if ($this->file('cover') && is_uploaded_file($this->file('cover')->path())) {
            $rules['cover'] = 'required|image|max:10240';
        }

        return $rules;
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        $data = $this->all();

        return $data;
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated()
    {
        $result = parent::validated();

        if (isset($result['cover'])) {
            if ($this->coverIsFile()) {
                $result['cover'] = Upload::uploadImage($result['cover'], 'manual', 450, 0, false)
                    ->getFileList()[0];
            } else {
                $result['cover'] = str_replace($this->root(), '', $result['cover']);
            }
        }

        $result['user_id'] = auth()->id();

        return $result;
    }

    /**
     * Check cover is file
     *
     * @return boolean
     */
    public function coverIsFile()
    {
        if (!$this->file('cover')) return false;
        
        return $this['cover'] instanceof \Illuminate\Http\Testing\File || is_uploaded_file($this->file('cover')->path());
    }
}
