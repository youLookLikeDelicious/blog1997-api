<?php
namespace App\Http\Requests\Traits;

trait DecodeParam
{
    /**
     * The key of decode parameter
     *
     * @var string
     */
    public $key = 'id';

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        $data = $this->all();

        if (isset($data[$this->key]) && !is_numeric($data[$this->key])) {
            $data[$this->key] = base64_decode($data[$this->key]);
        }

        return $data;
    }
}