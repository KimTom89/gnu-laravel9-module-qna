<?php

namespace Modules\Qna\Http\Requests;

use App\Models\Config;
use App\Rules\ValidateStringByFilter;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Qna\Entities\QnaConfig;

class UpdateQnaRequest extends FormRequest
{
    protected $config;

    protected $qnaConfig;

    public function __construct()
    {
        $this->config = Config::getConfig();
        $this->qnaConfig = QnaConfig::first();
    }

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
            'subject'    => ['required', new ValidateStringByFilter($this->config->cf_filter, ',')],
            'content'    => ['required', new ValidateStringByFilter($this->config->cf_filter, ',')],
            'qna_file.*' => ['sometimes', 'file', 'max:'.$this->qnaConfig->upload_file_size],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        // 카테고리 옵션 설정
        $validator->sometimes('qna_category_id', ['required', 'exists:qna_categories,id'], function ($input) {
            return $this->qnaConfig->use_category_depth >= 1;
        });

        // 이메일 입력 옵션 설정
        $emailRules = ['email'];
        if ($this->qnaConfig->req_email) {
            array_unshift($emailRules, 'required');
        }
        $validator->sometimes('user_email', $emailRules, function ($input) {
            return $this->qnaConfig->use_email;
        });
        // 휴대폰 입력 옵션 설정
        $hpRules = ['not_regex:/[^0-9\-]/'];
        if ($this->qnaConfig->req_hp) {
            array_unshift($hpRules, 'required');
        }
        $validator->sometimes('user_hp', $hpRules, function ($input) {
            return $this->qnaConfig->use_hp;
        });
    }
}
