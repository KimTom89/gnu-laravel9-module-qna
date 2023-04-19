<?php

namespace Modules\Qna\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Qna\Entities\QnaConfig;
use Modules\Qna\Http\Requests\Admin\QnaConfig\UpdateQnaConfigRequest;

class QnaConfigController extends Controller
{
    /**
     * 1:1문의 설정 페이지
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = [
            'qnaConfig' => QnaConfig::first() ?: new QnaConfig(),
        ];

        return view('qna::admin.qna-config.index', $data);
    }

    /**
     * 1:1문의 설정 저장.
     *
     * @param UpdateQnaConfigRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateQnaConfigRequest $request)
    {
        $qnaConfig = QnaConfig::first() ?: new QnaConfig();
        $qnaConfig->fill($request->safe()->all());
        $qnaConfig->save();

        return redirect()->route('admin.qna-config.index');
    }
}
