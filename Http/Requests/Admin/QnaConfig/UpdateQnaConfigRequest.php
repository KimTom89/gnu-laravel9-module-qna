<?php

namespace Modules\Qna\Http\Requests\Admin\QnaConfig;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQnaConfigRequest extends FormRequest
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
            'use_email'         => $this->use_email ?: 0,
            'req_email'         => $this->req_email ?: 0,
            'use_hp'            => $this->use_hp ?: 0,
            'req_hp'            => $this->req_hp ?: 0,
            'use_sms'           => $this->use_sms ?: 0,
            'use_editor'        => $this->use_editor ?: 0,
            'upload_file_count' => $this->upload_file_count ?: 0,
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
            'use_category_depth'    => [],
            'title'                 => ['required'],
            'skin'                  => ['required'],
            'mobile_skin'           => ['required'],
            'use_email'             => ['sometimes', 'boolean'],
            'req_email'             => ['sometimes', 'boolean'],
            'use_hp'                => ['sometimes', 'boolean'],
            'req_hp'                => ['sometimes', 'boolean'],
            'use_sms'               => ['sometimes', 'boolean'],
            'send_number'           => ['sometimes'],
            'admin_hp'              => ['sometimes'],
            'admin_email'           => ['sometimes'],
            'use_editor'            => ['sometimes', 'boolean'],
            'subject_length'        => ['required', 'numeric'],
            'mobile_subject_length' => ['required', 'numeric'],
            'page_rows'             => ['required', 'numeric'],
            'mobile_page_rows'      => ['required', 'numeric'],
            'image_width'           => ['required', 'numeric'],
            'upload_file_count'     => ['required', 'numeric', 'max:10'],
            'upload_file_size'      => ['required', 'numeric', 'max:204800'],
            'insert_content'        => [''],
            'include_head'          => [''],
            'include_tail'          => [''],
            'content_head'          => [''],
            'content_tail'          => [''],
            'mobile_content_head'   => [''],
            'mobile_content_tail'   => [''],
            'extra_1_subj'          => [''],
            'extra_2_subj'          => [''],
            'extra_3_subj'          => [''],
            'extra_4_subj'          => [''],
            'extra_5_subj'          => [''],
            'extra_1'               => [''],
            'extra_2'               => [''],
            'extra_3'               => [''],
            'extra_4'               => [''],
            'extra_5'               => [''],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required'              => ':attribute는 필수 입력 항목입니다.',
            'regex'                 => '올바른 형식으로 입력해 주세요.',
            'numeric'               => '숫자만 입력해 주세요.',
            'email'                 => '이메일 형식으로 입력해 주세요.',
            'upload_file_count.max' => '최대 :max개 까지 업로드 가능합니다.',
            'upload_file_size.max'  => '최대 :maxKB (200MB) 까지 업로드 가능합니다.',
        ];
    }
}
