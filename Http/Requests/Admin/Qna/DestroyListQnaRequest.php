<?php

namespace Modules\Qna\Http\Requests\Admin\Qna;

use Illuminate\Foundation\Http\FormRequest;

class DestroyListQnaRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'chk.*' => ['required', 'exists:qnas,id'],
        ];
    }
}
