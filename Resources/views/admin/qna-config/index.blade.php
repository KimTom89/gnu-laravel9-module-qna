<x-layout-admin :title="'1:1문의 설정'">
    <form id="qna_config_form" name="qna_config_form" method="POST" onsubmit="return qna_config_form_submit(this);" autocomplete="off"
        action="{{ route('admin.qna-config.update') }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="use_category_depth" value="{{ $qnaConfig->use_category_depth ?: 1 }}"/>
        <section>
            <h2 class="h2_frm">1:1문의 설정</h2>
            <div class="tbl_frm01 tbl_wrap">
                <table>
                    <caption>1:1문의 설정</caption>
                    <colgroup>
                        <col class="grid_4">
                        <col>
                    </colgroup>
                    <tbody>
                        <tr>
                            <th scope="row"><label for="title">타이틀<strong class="sound_only">필수</strong></label></th>
                            <td>
                                <input type="text" id="title" name="title" class="required frm_input"
                                    value="{{ $qnaConfig->title }}" size="40" required>
                                @if (Route::has('qna.index'))
                                    <a href="{{ route('qna.index') }}" class="btn_frmline">1:1문의 바로가기</a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="skin">스킨 디렉토리<strong class="sound_only">필수</strong></label></th>
                            <td>
                                <select id="skin" name="skin" required>
                                    <option value="">선택</option>
                                    <option value="theme/basic" @selected($qnaConfig->skin == 'theme/basic')>(테마) basic</option>
                                    <option value="basic" @selected($qnaConfig->skin == 'basic')>basic</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="mobile_skin">모바일 스킨 디렉토리<strong class="sound_only">필수</strong></label></th>
                            <td>
                                <select id="mobile_skin" name="mobile_skin" required>
                                    <option value="">선택</option>
                                    <option value="theme/basic" @selected($qnaConfig->mobile_skin == 'theme/basic')>(테마) basic</option>
                                    <option value="basic" @selected($qnaConfig->mobile_skin == 'basic')>basic</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">이메일 입력</th>
                            <td>
                                <input type="checkbox" id="use_email" name="use_email" value="1" @checked($qnaConfig->use_email)> 
                                <label for="use_email">보이기</label>
                                <input type="checkbox" id="req_email" name="req_email" value="1" @checked($qnaConfig->req_email)>
                                <label for="req_email">필수입력</label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">휴대폰 입력</th>
                            <td>
                                <input type="checkbox" id="use_hp" name="use_hp" value="1" @checked($qnaConfig->use_hp)>
                                <label for="use_hp">보이기</label>
                                <input type="checkbox" id="req_hp" name="req_hp" value="1" @checked($qnaConfig->req_hp)>
                                <label for="req_hp">필수입력</label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="use_sms">SMS 알림</label></th>
                            <td>
                                <span class="frm_info">
                                    휴대폰 입력을 사용하실 경우 문의글 등록시 등록자가 답변등록시 SMS 알림 수신을 선택할 수 있도록 합니다.
                                    <br>SMS 알림을 사용하기 위해서는 기본환경설정 &gt; <a href="http://3.35.173.159/g5-update/adm/config_form.php#anc_cf_sms">SMS 설정</a>
                                    을 하셔야 합니다.
                                </span>
                                <select id="use_sms" name="use_sms">
                                    <option value="0" @selected($qnaConfig->use_sms == '0')>사용안함</option>
                                    <option value="1" @selected($qnaConfig->use_sms == '1')>사용함</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="send_number">SMS 발신번호</label></th>
                            <td>
                                <span class="frm_info">SMS 알림 전송시 발신번호로 사용됩니다.</span>
                                <input type="text" id="send_number" name="send_number" class="frm_input" value="{{ $qnaConfig->send_number }}" size="30">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="admin_hp">관리자 휴대폰번호</label></th>
                            <td>
                                <span class="frm_info">
                                    관리자 휴대폰번호를 입력하시면 문의글 등록시 등록하신 번호로 SMS 알림이 전송됩니다.
                                    <br>SMS 알림을 사용하지 않으시면 알림이 전송되지 않습니다.
                                </span>
                                <input type="text" id="admin_hp" name="admin_hp" class="frm_input" value="{{ $qnaConfig->admin_hp }}" size="30">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="admin_email">관리자 이메일</label></th>
                            <td>
                                <span class="frm_info">관리자 이메일을 입력하시면 문의글 등록시 등록하신 이메일로 알림이 전송됩니다.</span> 
                                <input type="text" id="admin_email" name="admin_email" class="frm_input" value="{{ $qnaConfig->admin_email }}" size="50">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="use_editor">DHTML 에디터 사용</label></th>
                            <td>
                                <span class="frm_info">
                                    글작성시 내용을 DHTML 에디터 기능으로 사용할 것인지 설정합니다. 스킨에 따라 적용되지 않을 수 있습니다.
                                </span>
                                <select id="use_editor" name="use_editor">
                                    <option value="0" @selected($qnaConfig->use_editor == '0')>사용안함</option>
                                    <option value="1" @selected($qnaConfig->use_editor == '1')>사용함</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="subject_length">제목 길이<strong class="sound_only"> 필수</strong></label></th>
                            <td>
                                <span class="frm_info">목록에서의 제목 글자수</span>
                                <input type="text" id="subject_length" name="subject_length" class="required numeric frm_input"
                                    value="{{ $qnaConfig->subject_length }}" size="4" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="mobile_subject_len">모바일 제목 길이<strong class="sound_only">필수</strong></label>
                            </th>
                            <td>
                                <span class="frm_info">목록에서의 제목 글자수</span>
                                <input type="text" id="mobile_subject_length" name="mobile_subject_length" class="required numeric frm_input"
                                    value="{{ $qnaConfig->mobile_subject_length }}" size="4" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="page_rows">페이지당 목록 수<strong class="sound_only"> 필수</strong></label></th>
                            <td>
                                <input type="text" id="page_rows" name="page_rows" class="required numeric frm_input"
                                    value="{{ $qnaConfig->page_rows }}" size="4" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="mobile_page_rows">모바일 페이지당 목록 수<strong class="sound_only">필수</strong></label></th>
                            <td>
                                <input type="text" id="mobile_page_rows" name="mobile_page_rows" class="required numeric frm_input"
                                    value="{{ $qnaConfig->mobile_page_rows }}" size="4" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="image_width">이미지 폭 크기<strong class="sound_only"> 필수</strong></label></th>
                            <td>
                                <span class="frm_info">게시판에서 출력되는 이미지의 폭 크기</span>
                                <input type="text" id="image_width" name="image_width" class="required numeric frm_input"
                                    value="{{ $qnaConfig->image_width }}" size="4" required> 픽셀
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="upload_file_size">파일 업로드 수</label></th>
                            <td>
                                <span class="frm_info">최대 10개까지 업로드 가능합니다.</span>
                                <input type="text" id="upload_file_count" name="upload_file_count" class="numeric frm_input"
                                    value="{{ $qnaConfig->upload_file_count }}" size="6">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="upload_file_size">파일 업로드 용량<strong class="sound_only"> 필수</strong></label></th>
                            <td>
                                <span class="frm_info">최대 200M 이하 업로드 가능, 1 MB = 1,024 kbytes</span> 업로드 파일 한개당
                                <input type="text" id="upload_file_size" name="upload_file_size" class="required numeric frm_input"
                                    value="{{ $qnaConfig->upload_file_size }}" size="10" required> kbytes 이하
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="include_head">상단 파일 경로</label></th>
                            <td>
                                <span class="frm_info">
                                    동적 컴포넌트의 이름을 점 표기방법으로 입력해주세요. (예 : content.datepicker)<br>
                                    컴포넌트는 'resource/views/components' 경로에서 확인할 수 있습니다.
                                </span>
                                <input type="text" id="include_head" name="include_head" class="frm_input"
                                    value="{{ $qnaConfig->include_head }}" size="50">
                                @isset ($qnaConfig->include_head)
                                    @unlessexistComponent ($qnaConfig->include_head)
                                        <font color="red">해당 경로의 컴포넌트가 존재하지 않습니다.</font>
                                    @endexistComponent
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="include_tail">하단 파일 경로</label></th>
                            <td>
                                <span class="frm_info">
                                    동적 컴포넌트의 이름을 점 표기방법으로 입력해주세요. (예 : content.datepicker)<br>
                                    컴포넌트는 'resource/views/components' 경로에서 확인할 수 있습니다.
                                </span>
                                <input type="text" id="include_tail" name="include_tail" class="frm_input"
                                    value="{{ $qnaConfig->include_tail }}" size="50">
                                @isset ($qnaConfig->include_tail)
                                    @unlessexistComponent ($qnaConfig->include_tail)
                                        <font color="red">해당 경로의 컴포넌트가 존재하지 않습니다.</font>
                                    @endexistComponent
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="content_head">상단 내용</label></th>
                            <td>
                                <x-editor content="{{ old('content_head') ?: $qnaConfig->content_head }}" tagName="content_head" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="content_tail">하단 내용</label></th>
                            <td>
                                <x-editor content="{{ old('content_tail') ?: $qnaConfig->content_tail }}" tagName="content_tail" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="mobile_content_head">모바일 상단 내용</label></th>
                            <td>
                                <x-editor content="{{ old('mobile_content_head') ?: $qnaConfig->mobile_content_head }}" tagName="mobile_content_head" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="mobile_content_tail">모바일 하단 내용</label></th>
                            <td>
                                <x-editor content="{{ old('mobile_content_tail') ?: $qnaConfig->mobile_content_tail }}" tagName="mobile_content_tail" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="insert_content">글쓰기 기본 내용</label></th>
                            <td>
                                <textarea id="insert_content" name="insert_content" rows="5">{{ $qnaConfig->insert_content }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">여분필드1</th>
                            <td class="td_extra">
                                <label for="extra_1_subj">여분필드 1 제목</label>
                                <input type="text" id="extra_1_subj" name="extra_1_subj" class="frm_input" value="{{ $qnaConfig->extra_1_subj }}">
                                <label for="extra_1">여분필드 1 값</label>
                                <input type="text" id="extra_1" name="extra_1" class="frm_input" value="{{ $qnaConfig->extra_1 }}">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">여분필드2</th>
                            <td class="td_extra">
                                <label for="extra_2_subj">여분필드 2 제목</label>
                                <input type="text" id="extra_2_subj" name="extra_2_subj" class="frm_input" value="{{ $qnaConfig->extra_2_subj }}">
                                <label for="extra_2">여분필드 2 값</label>
                                <input type="text" id="extra_2" name="extra_2" class="frm_input" value="{{ $qnaConfig->extra_2 }}">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">여분필드3</th>
                            <td class="td_extra">
                                <label for="extra_3_subj">여분필드 3 제목</label>
                                <input type="text" id="extra_3_subj" name="extra_3_subj" class="frm_input" value="{{ $qnaConfig->extra_3_subj }}">
                                <label for="extra_3">여분필드 3 값</label>
                                <input type="text" id="extra_3" name="extra_3" class="frm_input" value="{{ $qnaConfig->extra_3 }}">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">여분필드4</th>
                            <td class="td_extra">
                                <label for="extra_4_subj">여분필드 4 제목</label>
                                <input type="text" id="extra_4_subj" name="extra_4_subj" class="frm_input" value="{{ $qnaConfig->extra_4_subj }}">
                                <label for="extra_4">여분필드 4 값</label>
                                <input type="text" id="extra_4" name="extra_4" class="frm_input" value="{{ $qnaConfig->extra_4 }}">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">여분필드5</th>
                            <td class="td_extra">
                                <label for="extra_5_subj">여분필드 5 제목</label>
                                <input type="text" id="extra_5_subj" name="extra_5_subj" class="frm_input" value="{{ $qnaConfig->extra_5_subj }}">
                                <label for="extra_5">여분필드 5 값</label>
                                <input type="text" id="extra_5" name="extra_5" class="frm_input" value="{{ $qnaConfig->extra_5 }}">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="btn_fixed_top">
            <input type="submit" class="btn_submit btn" value="확인" accesskey="s">
        </div>

    </form>

    {{-- 그누보드5에서는 [KVE-2018-0289] 취약점 으로 인해 파일 경로 수정시에만 자동등록방지 기능이 추가되어있음. --}}
    <script>
        function qna_config_form_submit(f) {
            f.content_head.value = getEditorContent('content_head');
            f.content_tail.value = getEditorContent('content_tail');
            f.mobile_content_head.value = getEditorContent('mobile_content_head');
            f.mobile_content_tail.value = getEditorContent('mobile_content_tail');

            return true;
        }
    </script>
</x-layout-admin>
