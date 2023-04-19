<?php

namespace Modules\Qna\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Qna\Entities\QnaCategory;
use Modules\Qna\Http\Requests\Admin\QnaCategory\CreateQnaCategoryRequest;
use Modules\Qna\Http\Requests\Admin\QnaCategory\UpdateListQnaCategoryRequest;

class QnaCategoryController extends Controller
{
    /**
     * Q&A 분류 목록 페이지
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $qnaCategories = QnaCategory::with(['children' => function($query){
                $query->orderBy('position');
            }])
            ->whereNull('parent_id')
            ->orderBy('position')
            ->get();
        
        return view('qna::admin.qna-category.index', ['qnaCategories' => $qnaCategories]);
    }

    /**
     * Q&A 분류 등록 페이지
     *
     * @param QnaCategory $qnaCategory
     * @return \Illuminate\Contracts\View\View
     */
    public function create(QnaCategory $qnaCategory)
    {
        return view('qna::admin.qna-category.create', ['qnaCategory' => $qnaCategory]);
    }

    /**
     * Q&A 분류 저장
     *
     * @param CreateQnaCategoryRequest $request
     * @return void
     */
    public function store(CreateQnaCategoryRequest $request, QnaCategory $qnaCategory)
    {
        $validated = $request->safe()->all();
        $qnaCategory->fill($validated);

        if (isset($validated['parent_id'])) {
            $qnaCategory->parent_id = $validated['parent_id'];
            $position = QnaCategory::where('parent_id', $validated['parent_id'])->max('position');
        } else {
            $position = QnaCategory::whereNull('parent_id')->max('position');
        }
        $qnaCategory->position = $position + 1;
        $qnaCategory->save();

        echo '<script>alert("성공"); opener.location.reload(); window.close();</script>';
    }

    /**
     * Q&A 분류 일괄수정
     *
     * @param UpdateListQnaCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateList(UpdateListQnaCategoryRequest $request)
    {
        $validated = $request->safe()->all();

        foreach ($validated['id'] as $id) {
            $qnaCategory = QnaCategory::find($id);
            $qnaCategory->subject = $validated['subject'][$id];
            $qnaCategory->position = $validated['position'][$id];
            $qnaCategory->is_use = isset($validated['is_use'][$id]) ? $validated['is_use'][$id] : 0;
            $qnaCategory->save();
        }

        return redirect()->route('admin.qna-category.index');
    }

    /**
     * Q&A 분류 삭제
     *
     * @param qnaCategory $qnaCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(QnaCategory $qnaCategory)
    {
        $qnaCategory->delete();

        if (is_null($qnaCategory->parent_id)) {
            $qnaCategory->children()->delete();
        }

        return redirect()->route('admin.qna-category.index');
    }
}
