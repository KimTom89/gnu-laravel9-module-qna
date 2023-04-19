<?php

namespace Modules\Qna\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Service\ImageService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Qna\Entities\Qna;
use Modules\Qna\Entities\QnaCategory;
use Modules\Qna\Http\Requests\Admin\Qna\DestroyListQnaRequest;
use Modules\Qna\Http\Requests\Admin\Qna\UpdateQnaRequest;

class QnaAdminController extends Controller
{
    /**
     * Q&A 목록 페이지
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $config = Config::getConfig();
        $paginate = $config->cf_page_rows ?: 10;
        $sst = $request->filled('sst') ? $request->get('sst') : 'id';
        $sod = $request->filled('sod') ? $request->get('sod') : 'DESC';

        $qnas = Qna::with(['category', 'user'])
            ->when($request->get('qna_category_id'), function($query, $category) {
                $query->where('qna_category_id', $category);
            })
            ->when($request->get('status') !== null, function($query) use ($request) {
                $query->where('status', $request->get('status'));
            })
            ->when($request->get('stx'), function($query, $stx) use ($request) {
                if ($request->get('sfl') == 'sub_con') {
                    $query->where(function ($query) use ($stx) {
                        $query->where('subject', 'LIKE', "%{$stx}%")
                            ->orWhere('content', 'LIKE', "%{$stx}%");
                    });
                } else {
                    $query->where($request->get('sfl'), 'LIKE', "%{$stx}%");
                }
            })
            ->orderBy($sst, $sod)
            ->paginate($paginate);

        $data = [
            'statistics' => (object)[
                'total' => number_format(Qna::count()),
                'pending' => number_format(Qna::where('status', 0)->count()),
                'draft' => number_format(Qna::where('status', 1)->count()),
                'answer' => number_format(Qna::where('status', 2)->count()),
            ],
            'qnaCategories' => QnaCategory::whereNull('parent_id')->get(),
            'qnas' => $qnas,
            'direction' => $request->sod == 'DESC' ? 'ASC' : 'DESC',
        ];

        return view('qna::admin.qna.index', $data);
    }

    /**
     * Q&A 수정 페이지
     *
     * @param ImageService  $imageService
     * @param Qna $qna
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(ImageService $imageService, Qna $qna)
    {
        $files = (object)['files' => [], 'images' => []];

        foreach ($qna->files as $file) {
            if ($imageService->isImageFile($file->path)) {
                $file->width = $imageService->getImageWidth($file->path, 200);
                array_push($files->images, $file);
            } else {
                array_push($files->files, $file);
            }
        }

        $data = [
            'qnaCategories' => QnaCategory::all(),
            'qna' => $qna,
            'qnaFiles' => $files
        ];

        return view('qna::admin.qna.edit', $data);
    }

    /**
     * Q&A 수정
     * 
     * @todo sms/메일 전송
     * @param UpdateQnaRequest $request
     * @param Qna $qna
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateQnaRequest $request, Qna $qna)
    {
        $qna->fill($request->safe()->all());

        // 답변 완료처리
        if (($qna->isDirty('status') && $qna->status == 2)) {
            $qna->answer_date = Carbon::now();
            // SMS
            if ($qna->is_receive_sms && $qna->user_hp) {
                // $this-SmsService->sendSms($to, $from, $message);
            }
            // 메일
            if ($qna->is_receive_email && $qna->user_email) {

            }
        }
        $qna->save();

        return redirect()
            ->route('admin.qna.edit', ['qna' => $qna])
            ->with('status', '저장되었습니다.');
    }

    /**
     * Q&A 일괄삭제
     *
     * @param DestroyListQnaRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyList(DestroyListQnaRequest $request)
    {
        $validated = $request->safe()->all();
        foreach ($validated['chk'] as $qnaId) {
            $qna = Qna::find($qnaId);
            foreach ($qna->files as $file) {
                $file->delete();
                Storage::delete($file->path);
            }
            $qna->delete();
        }

        return redirect()
            ->route('admin.qna.index')
            ->with('status', '정상적으로 삭제되었습니다.');
    }
}
