<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class CheckAuthRequest extends Request
{

    /**
     * Validation rules to be applied to the input
     * @return void
     */
    public function rules()
    {
        return [
            "email" => 'required',
            "timezone" => 'required',
        ];
    }
}
