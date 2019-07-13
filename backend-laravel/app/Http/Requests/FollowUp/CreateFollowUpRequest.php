<?php

namespace App\Http\Requests\FollowUp;

use App\Http\Requests\Request;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class CreateFollowUpRequest extends Request
{

    /**
     * Validation rules to be applied to the input
     * @return void
     */
    public function rules()
    {
        return [
            "followup_type" => 'required',
            "message_id"    => 'required',
        ];
    }
}
