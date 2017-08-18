<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RotaAcessoRequest extends FormRequest
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
            'parent_id'=>'integer|nullable',
            'text'=>'required|min:3',
            'disabled'=>'required|boolean',
            'prioridade'=>'integer',
            'roles'=>'array',
        ];
    }
}
