<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterResquest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($id=null)
    {
        return [
            'nome'                  => 'string|min:3|nullable',
            'email'                 => 'required|email|unique:newsletters,email'.(isset($id)?','.$id:''),
            'plataforma'            => 'string|min:2|in:mobile,portal|nullable',
        ];
    }
}
