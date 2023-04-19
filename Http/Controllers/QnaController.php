<?php

namespace Modules\Qna\Http\Controllers;

use App\Facades\Utils;
use App\Models\Config;
use App\Service\ImageService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Qna\Entities\Qna;
use Modules\Qna\Entities\QnaCategory;
use Modules\Qna\Entities\QnaConfig;
use Modules\Qna\Entities\QnaFile;
use Modules\Qna\Http\Requests\CreateQnaRequest;
use Modules\Qna\Http\Requests\UpdateQnaRequest;

class QnaController extends Controller
{
    protected $config;

    protected $qnaConfig;

   /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = Config::getConfig();
        $this->qnaConfig = QnaConfig::first();
    }
    
    /**
     * Q&A 목록 페이지
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if (is_null($this->qnaConfig)) {
            return back()
                ->withErrors('QnA설정이 등록되지않아 이용하실 수 없습니다.');
        }
        if (Auth::guest()) {
            return redirect()->route('login', ['return_url' => route(Route::currentRouteName())])
                ->withErrors('로그인 후 이용 가능합니다.');
        }

        $paginate = Utils::isMobile() ? $this->qnaConfig->mobile_page_rows : $this->qnaConfig->page_rows;
        $subjectLimit = Utils::isMobile() ? $this->qnaConfig->mobile_subject_length : $this->qnaConfig->subject_length;

        $qnas = Qna::with(['category', 'user', 'files'])
            ->when($request->get('category'), function($query, $category_id) {
                $query->leftJoin('qna_categories', 'qnas.qna_category_id', '=', 'qna_categories.id')
                    ->select('qnas.*');
                $query->where(function ($join) use ($category_id){
                    $join->where('qna_categories.id', $category_id)
                        ->orWhere('qna_categories.parent_id', $category_id);
                });
            })
            ->when($request->get('stx'), function($query, $stx) use ($request) {
                $query->where($request->get('sfl'), 'LIKE', "%{$stx}%");
            })
            ->where('mb_id', Auth::user()->mb_id)
            ->orderByDesc('qnas.created_at')
            ->paginate($paginate);

        $total = $qnas->total();

        /**
         * 결과 데이터 처리
         * @todo 목록번호 공통처리
         */
        foreach ($qnas as $index => $qna) {
            $qna->subject = Str::limit($qna->subject, $subjectLimit);
            $qna->listNumber = $total - ($qnas->currentPage() - 1) * $paginate - $index;
        }

        $data = [
            'qnaConfig' => $this->qnaConfig,
            'qnaCategories' => QnaCategory::whereNull('parent_id')->where('is_use', 1)->get(),
            'total' => number_format($total),
            'qnas' => $qnas,
        ];

        return view('qna::index', $data);
    }

    /**
     * Q&A 등록 페이지
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $qna = new Qna();
        /**
         * 연관된 문의 정보 조회
         * @todo is_dhtml_editor 구분 (qa_use_editor 설정)
         */
        if ($request->filled('related_qna_id')) {
            $relatedQna = Qna::find($request->get('related_qna_id'));
            $qna->content = '<br><br>====== 이전 답변내용 =======<br><br>' . $relatedQna->content;
        }
        
        $data = [
            'qnaConfig' => $this->qnaConfig,
            'qnaCategories' => QnaCategory::whereNull('parent_id')->where('is_use', 1)->get(),
            'qna' => $qna,
            'action' => route('qna.store')
        ];
        
        return view('qna::edit', $data);
    }

    /**
     * Q&A 저장
     *
     * @param CreateQnaRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateQnaRequest $request)
    {
        $qna = new Qna($request->all());
        $qna->user_name = Auth::user()->name;
        $qna->mb_id = Auth::user()->mb_id;
        $qna->ip = FacadesRequest::ip();
        $qna->save();

        // 파일 저장
        if ($request->file('qna_file')) {
            foreach ($request->file('qna_file') as $file) {
                if ($path = Storage::putFile('public/qna_file', $file)) {
                    $qnaFile = new QnaFile();
                    $qnaFile->path = $path;
                    $qnaFile->file_name = $file->getClientOriginalName();
                    $qnaFile->file_size = $file->getSize();
                    $qna->files()->save($qnaFile);
                }
            }
        }
        /**
         * @todo sms 전송
         */
        if (trim($this->qnaConfig->admin_hp)) {
            // $this-SmsService->sendSms($to, $from, $message);
        }

        return redirect()->route('qna.index')
            ->with('status', '정상적으로 등록되었습니다.');
    }

    /**
     * Q&A 상세 페이지
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Qna $qna)
    {
        $response = Gate::inspect('view', $qna);
        if (!$response->allowed()) {
            return back()->withErrors($response->message());
        }

        $subjectLimit = Utils::isMobile() ? $this->qnaConfig->mobile_subject_length : $this->qnaConfig->subject_length;
        $qna->subject = Str::limit($qna->subject, $subjectLimit);

        $files = (object)['files' => [], 'images' => []];
        $imageService = new ImageService();
        foreach ($qna->files as $file) {
            if ($imageService->isImageFile($file->path)) {
                $file->width = $imageService->getImageWidth($file->path, $this->qnaConfig->image_width);
                array_push($files->images, $file);
            } else {
                array_push($files->files, $file);
            }
        }

        $data = [
            'qna' => $qna,
            'qnaConfig' => $this->qnaConfig,
            'qnaFiles' => $files
        ];
        
        return view('qna::view', $data);
    }

    /**
     * Q&A 수정 페이지
     *
     * @param Qna $qna
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Qna $qna)
    {
        $response = Gate::inspect('update', $qna);
        if (!$response->allowed()) {
            return back()->withErrors($response->message());
        }

        $data = [
            'qnaConfig' => $this->qnaConfig,
            'qnaCategories' => QnaCategory::whereNull('parent_id')->where('is_use', 1)->get(),
            'qna' => $qna,
            'action' => route('qna.update', ['qna' => $qna])
        ];

        return view('qna.edit', $data);
    }

    /**
     * Q&A 수정
     *
     * @param UpdateQnaRequest $request
     * @param Qna $qna
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateQnaRequest $request, Qna $qna)
    {
        $response = Gate::inspect('update', $qna);
        if (!$response->allowed()) {
            return back()->withErrors($response->message());
        }
        $qna->fill($request->all());
        $qna->ip = FacadesRequest::ip();
        $qna->save();
        
        // 파일 삭제
        if ($request->get('qna_file_delete')) {
            foreach ($request->get('qna_file_delete') as $fileId) {
                $qnaFile = QnaFile::find($fileId);
                $qnaFile->delete();
                Storage::delete($qnaFile->path);
            }
        }

        // 파일 저장
        if ($request->file('qna_file')) {
            foreach ($request->file('qna_file') as $index => $file) {
                if ($path = Storage::putFile('public/qna_file', $file)) {
                    $qnaFile = QnaFile::find($request->get('qna_file_id')[$index]) ?: new QnaFile();
                    if (isset($qnaFile->path)) {
                        Storage::delete($qnaFile->path);
                    }
                    $qnaFile->path = $path;
                    $qnaFile->file_name = $file->getClientOriginalName();
                    $qnaFile->file_size = $file->getSize();
                    $qna->files()->save($qnaFile);
                }
            }
        }

        return redirect()->route('qna.show', ['qna' => $qna])
            ->with('status', '정상적으로 등록되었습니다.');
    }

    /**
     * Q&A 삭제
     *
     * @param Qna $qna
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Qna $qna)
    {
        $response = Gate::inspect('delete', $qna);
        if (!$response->allowed()) {
            return back()->withErrors($response->message());
        }

        $qna->delete();
        // 파일 삭제
        foreach ($qna->files as $file) {
            $file->delete();
            Storage::delete($file->path);
        }

        return redirect()->route('qna.index')
            ->with('status', '정상적으로 삭제되었습니다.');
    }
}
