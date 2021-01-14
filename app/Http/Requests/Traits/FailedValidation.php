<?php
namespace App\Http\Requests\Traits;

use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

trait FailedValidation
{
    /**
     * Handle a failed validation attempt.
     * 
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        // prevent infinite redirect
        throw (new ValidationException($validator,
            response()->json(['errors' => $validator->errors()], 422)));
    }
}