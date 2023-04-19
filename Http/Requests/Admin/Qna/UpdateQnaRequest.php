<?php

namespace Modules\Qna\Http\Requests\Admin\Qna;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQnaRequest extends FormRequest
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
            'qna_category_id' => ['sometimes', 'nullable', 'exists:qna_categories,id'],
            'status' => ['required', 'in:0,1,2'],
            'is_receive_email' => ['sometimes', 'boolean'],
            'is_receive_sms' => ['sometimes', 'boolean'],
            'subject' => ['required'],
            'content' => ['required'],
            'answer_subject' => ['sometimes', 'nullable'],
            'answer_content' => ['sometimes', 'nullable'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_receive_email' => $this->is_receive_email ?: 0,
            'is_receive_sms' => $this->is_receive_sms ?: 0,
        ]);
    }
}
