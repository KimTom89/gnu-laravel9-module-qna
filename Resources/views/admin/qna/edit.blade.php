<x-layout-admin :title="'Q&A 상세내용'">
    <form name="qna_form" method="POST" action="{{ route('admin.qna.update', ['qna' => $qna]) }}" onsubmit="return qna_form_submit(this);">
        @csrf
        @method('PUT')
        <div class="local_desc01 local_desc">
            <p>1:1 문의에 답변하실 수 있으며, 문의 내용의 수정도 가능합니다.</p>
        </div>

        <div class="tbl_frm01 tbl_wrap">
            <table>
                <caption>1:1문의 수정</caption>
                <colgroup>
                    <col class="grid_4">
                    <col class="grid_14">
                    <col class="grid_4">
                    <col class="grid_14">
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="row">회원정보</th>
                        <td>
                            <x-content.user_side_view :user="$qna->user" />
                        </td>
                        <th scope="row">이메일</th>
                        <td>
                            @if ($qna->user_email)
                                {{ $qna->user_email }}
                                <input type="checkbox" name="is_receive_email" value="1" @checked($qna->is_receive_email)> 답변알림을 이메일로 수신
                            @else
                                없음
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">카테고리 {{ $qna->category->id }} </th>
                        <td>
                            <select id="qna_category_id" name="qna_category_id">
                                <option value="">선택</option>
                                @foreach ($qnaCategories as $category)
                                    <option value="{{ $category->id }}" @selected(Utils::isSelectedOld($category->id, 'qna_category_id', $qna->category->id))>
                                        {{ $category->subject }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <th scope="row">휴대폰</th>
                        <td>
                            @if ($qna->user_hp)
                                {{ $qna->user_hp }}
                                <input type="checkbox" name="is_receive_sms" value="1" @checked($qna->is_receive_sms)> 답변알림을 SMS로 수신
                            @else
                                없음
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="subject">제목</label></th>
                        <td>
                            <input type="text" id="subject" name="subject" class="frm_input required" value="{{ $qna->subject }}"
                                required size="95">
                        </td>
                        <th scope="row"><label for="subject">상태</label></th>
                        <td>
                            <select name="status">
                                <option value="0" @selected($qna->status == 0)>접수</option>
                                <option value="1" @selected($qna->status == 1)>진행 중</option>
                                <option value="2" @selected($qna->status == 2)>답변완료</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="content">내용</label></th>
                        <td>
                            <x-editor content="{{ old('content') ?: $qna->content }}" tagName="content" />
                        </td>
                        <th scope="row"><label for="content">첨부파일</label></th>
                        <td>
                            @if (count($qnaFiles->images) > 0)
                                <label for="content"><b>이미지</b></label>
                                <div>
                                    @foreach ($qnaFiles->images as $image)
                                        <img src="{{ asset(Storage::url($image->path)) }}" width="{{ $image->width }}">
                                    @endforeach
                                </div>
                            @endif
                            @if (count($qnaFiles->files) > 0)
                                <label for="content"><b>첨부파일</b></label>
                                <div>
                                    @foreach ($qnaFiles->files as $file)
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                        <a href="{{ asset(Storage::url($file->path)) }}" class="view_file_download"
                                            download="{{ $file->file_name }}">
                                            <strong>{{ $file->file_name }}</strong>
                                        </a>
                                        <br>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="answer_subject">답변 제목</label></th>
                        <td colspan="3">
                            <input type="text" id="answer_subject" name="answer_subject" class="frm_input"
                                value="{{ $qna->answer_subject }}" size="150">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="answer_content">답변 내용</label></th>
                        <td colspan="3">
                            <x-editor content="{{ old('answer_content') ?: $qna->answer_content }}" tagName="answer_content" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="btn_fixed_top">
            <a href="{{ route('admin.qna.index') . '?' . Request::getQueryString() }}" class="btn btn_02">목록</a>
            <input type="submit" class="btn_submit btn" accesskey="s" value="확인">
        </div>
    </form>

    <script>
        function qna_form_submit(f) {

            f.content.value = getEditorContent('content');
            f.answer_content.value = getEditorContent('answer_content');

            return true;
        }
    </script>
</x-layout-admin>
