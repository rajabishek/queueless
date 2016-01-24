<?php 

namespace Queueless\Http\Controllers;

use Illuminate\Http\Request;
use Queueless\Exceptions\ValidationException;
use Queueless\Services\Response\CanRespondInJson;

class ApiController extends Controller 
{   
    /**
     * Trait to handle responding in JSON with proper status codes.
     *
     * @see \Queueless\Services\Response\CanRespondInJson
     */
    use CanRespondInJson;

    /**
     * Throw the failed validation exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function throwValidationException(Request $request, $validator)
    {
        throw new ValidationException($validator->errors());
    }
}