<?php

namespace Modules\Qna\Http\Requests\Admin\QnaCategory;

use Illuminate\Foundation\Http\FormRequest;

class CreateQnaCategoryRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_use' => $this->is_use ?: 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'parent_id' => ['sometimes', 'nullable', 'exists:qna_categories,id'],
            'subject' => ['required'],
            'is_use' => ['sometimes', 'boolean'],
        ];
    }
}
