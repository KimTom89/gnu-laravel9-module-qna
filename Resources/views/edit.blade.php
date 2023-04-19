<x-layout :title="$qnaConfig->title">
    <x-slot:css>
        <link rel="stylesheet" href="{{ asset('css/skin/qna/default.css') }}">
    </x-slot:css>

    <h2 id="container_title"><span title="{{ $qnaConfig->title }}">{{ $qnaConfig->title }}</span></h2>
    
    <x-qna::head :qnaConfig="$qnaConfig"/>
    
    <section id="bo_w">
        <h2>{{ $qnaConfig->title }} 작성</h2>
        <form id="fwrite" name="fwrite" action="{{ $action }}" onsubmit="return fwrite_submit(this);" method="POST"
            enctype="multipart/form-data" autocomplete="off">
            @csrf
            @if ($qna->id)
                @method('PUT')
            @endif
            <input type="hidden" name="html" value="1">
            <input type="hidden" name="related_qna_id" value="{{ Request::get('related_qna_id') ?: '' }}">
            <input type="hidden" id="qna_category_id" name="qna_category_id" value="{{ $qna->qna_category_id }}">

            <div class="form_01">
                <ul>
                    <li class="bo_w_select write_div">
                        <label class="sound_only" for="qna_category_id">분류<strong>필수</strong></label>
                        <select id="qna_category_id" name="qna_category_id" required>
                            <option value="">분류를 선택하세요</option>
                            @foreach ($qnaCategories as $category)
                                <option value="{{ $category->id }}" 
                                    @selected($category->id == old('qna_category_id') || $category->id == $qna->qna_category_id)>
                                    {{ $category->subject }}
                                </option>
                            @endforeach
                        </select>
                    </li>

                    @if ($qnaConfig->use_email)
                        <li class="bo_w_mail chk_box">
                            <label class="sound_only" for="user_email">이메일</label>
                            <input type="text" id="user_email" name="user_email" @class(['frm_input', 'full_input', 'email', 'required' => $qnaConfig->req_email])
                                value="{{ old('user_email') ?: $qna->user_email }}" size="50" maxlength="100" placeholder="이메일" @if ($qnaConfig->req_email) required @endif>
                            <input type="checkbox" id="is_receive_email" name="is_receive_email" class="selec_chk"
                                value="1" @checked(old('is_receive_email') || $qna->is_receive_email)>
                            <label class="frm_info" for="is_receive_email">
                                <span></span>답변받기
                            </label>
                        </li>
                    @endif

                    @if ($qnaConfig->use_hp)
                        <li class="bo_w_hp chk_box">
                            <label class="sound_only" for="user_hp">휴대폰</label>
                            <input type="text" id="user_hp" name="user_hp" @class(['frm_input', 'full_input', 'required' => $qnaConfig->req_hp])
                                value="{{ old('user_hp') ?: $qna->user_hp }}" size="30" placeholder="휴대폰" @if ($qnaConfig->req_hp) required @endif>
                            @if ($qnaConfig->use_sms)
                                <input type="checkbox" id="is_receive_sms" name="is_receive_sms" class="selec_chk"
                                    value="1" @checked(old('is_receive_sms') || $qna->is_receive_sms)>
                                <label class="frm_info" for="is_receive_sms">
                                    <span></span>답변등록 SMS알림 수신
                                </label>
                            @endif
                        </li>
                    @endif

                    <li class="bo_w_sbj">
                        <label class="sound_only" for="subject">제목<strong class="sound_only">필수</strong></label>
                        <input type="text" id="subject" name="subject" class="frm_input full_input required"
                            value="{{ old('subject') ?: $qna->subject }}" size="50" maxlength="255" placeholder="제목" required>
                    </li>

                    <li class="content_wrap smarteditor2">
                        <label class="sound_only" for="content">내용<strong class="sound_only">필수</strong></label>
                        <x-editor content="{{ old('content') ?: $qna->content }}" tagName="content" />
                    </li>
                    @for ($i = 0; $i < $qnaConfig->upload_file_count; $i++)
                        <li class="bo_w_flie">
                            <div class="file_wr">
                                <label class="lb_icon" for="qna_file_{{ $i }}">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    <span class="sound_only"> 파일 #{{ $i + 1 }}</span>
                                </label>
                                <input type="hidden" name="qna_file_id[]" value="{{ isset($qna->files[$i]) ? $qna->files[$i]->id : '' }}">
                                <input type="file" id="qna_file_{{ $i }}" name="qna_file[]" class="frm_file"
                                    title="파일첨부 {{ $i + 1 }} :  용량 {{ number_format($qnaConfig->uploade_file_size) }} 바이트 이하만 업로드 가능">
                                @isset ($qna->files[$i])
                                    <input type="checkbox" id="qna_file_delete_{{ $i }}" name="qna_file_delete[]" value="{{ $qna->files[$i]->id }}">
                                    <label for="qna_file_delete_{{ $i }}">{{ $qna->files[$i]->file_name }} 파일 삭제</label>
                                @endisset
                            </div>
                        </li>
                    @endfor
                </ul>
            </div>

            <div class="btn_confirm write_div">
                <a href="{{ url()->previous() }}" class="btn_cancel btn">취소</a>
                <button type="submit" id="btn_submit" class="btn_submit btn" accesskey="s">작성완료</button>
            </div>

        </form>

        <script>
            function html_auto_br(obj) {
                if (obj.checked) {
                    result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
                    if (result)
                        obj.value = "2";
                    else
                        obj.value = "1";
                } else
                    obj.value = "";
            }

            function fwrite_submit(f) {
                f.content.value = getEditorContent('content');

                check_field(f.content, "내용을 입력하세요.");

                document.getElementById("btn_submit").disabled = "disabled";

                return true;
            }
        </script>
    </section>
    
    <x-qna::tail :qnaConfig="$qnaConfig"/>

</x-layout>
