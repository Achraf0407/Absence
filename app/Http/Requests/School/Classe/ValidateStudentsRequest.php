<?php

namespace App\Http\Requests\School\Classe;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ValidateStudentsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'firstname'  => 'required|max:255',
            'lastname'   => 'required|max:255',
            'subject'     => 'required|max:255',
            'prof'        => 'required|max:255',
        ];
    }
}
