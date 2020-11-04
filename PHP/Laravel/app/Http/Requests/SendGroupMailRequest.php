<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendGroupMailRequest extends FormRequest
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
            'attachment.*' => 'sometimes|file|nullable',
            'mail' => 'required|string',
            'mail_id' => 'string',
            'subject' => 'required|string',
            'from_mail' => 'email',
            'candidates' => 'required|json',
            'current_user' => 'json'
        ];
    }
}
