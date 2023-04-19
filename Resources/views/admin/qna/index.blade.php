<x-layout-admin :title="'Q&A 목록'">
    <div class="local_ov01 local_ov">
        <a href="{{ route('admin.qna.index') }}" @class(['btn_ov01', 'active' => Request::get('status') === null]) data-tooltip-text="전체를 출력합니다.">
            <span class="ov_txt">전체 문의내역</span>
            <span class="ov_num">{{ $statistics->total }}건</span>
        </span>
        <a href="{{ URL::current() }}?status=0" @class(['btn_ov01', 'active' => Request::get('status') === '0']) data-tooltip-text="접수된 건만 출력합니다.">
            <span class="ov_txt">접수</span>
            <span class="ov_num">{{ $statistics->pending }}건</span>
        </a>
        <a href="{{ URL::current() }}?status=1" @class(['btn_ov01', 'active' => Request::get('status') === '1']) data-tooltip-text="진행 중인 건만 출력합니다.">
            <span class="ov_txt">진행중</span>
            <span class="ov_num">{{ $statistics->draft }}건</span>
        </a>
        <a href="{{ URL::current() }}?status=2" @class(['btn_ov01', 'active' => Request::get('status') === '2']) data-tooltip-text="딥변이 완료된 건만 출력합니다.">
            <span class="ov_txt">답변완료</span>
            <span class="ov_num">{{ $statistics->answer }}건</span>
        </a>
    </div>

    <form name="flist" class="local_sch01 local_sch">
        <input type="hidden" name="status" value="{{ Request::get('status') }}">
        <label class="sound_only" for="qna_category_id">카테고리 선택</label>
        <select id="qna_category_id" name="qna_category_id">
            <option value="">전체 카테고리</option>
            @foreach ($qnaCategories as $category)
                <option value="{{ $category->id }}" @selected(Request::get('qna_category_id') == $category->id)>
                    {{ $category->subject }}
                </option>
            @endforeach
        </select>

        <label class="sound_only" for="sfl">검색대상</label>
        <select id="sfl" name="sfl">
            <option value="subject" @selected(Request::get('sfl') == 'subject')>제목</option>
            <option value="content" @selected(Request::get('sfl') == 'content')>내용</option>
            <option value="sub_con" @selected(Request::get('sfl') == 'sub_con')>제목+내용</option>
            <option value="mb_id" @selected(Request::get('sfl') == 'mb_id')>아이디</option>
            <option value="user_name" @selected(Request::get('sfl') == 'user_name')>이름</option>
        </select>

        <label class="sound_only" for="stx">검색어<strong class="sound_only"> 필수</strong></label>
        <input type="text" id="stx" name="stx" class="frm_input" value="{{ Request::get('stx') }}">
        <input type="submit" class="btn_submit" value="검색">

    </form>

    <form name="fitemqalist" method="POST" action="{{ route('admin.qna.list.destroy') }}" onsubmit="return fitemqalist_submit(this);" autocomplete="off">
        @csrf
        @method('DELETE')
        <div id="itemqalist" class="tbl_head01 tbl_wrap">
            <table>
                <caption>1:1문의 목록</caption>
                <thead>
                    <tr>
                        <th scope="col">
                            <label class="sound_only" for="chkall">1:1문의</label>
                            <input type="checkbox" id="chkall" name="chkall" value="1" onclick="check_all(this.form)">
                        </th>
                        <th scope="col">
                            <a href="{{ Request::fullUrlWithQuery(['sst' => 'qna_category_id', 'sod' => (Request::get('sst') == 'qna_category_id') ? $direction : 'ASC']) }}">카테고리</a>
                        </th>
                        <th scope="col">
                            <a href="{{ Request::fullUrlWithQuery(['sst' => 'subject', 'sod' => (Request::get('sst') == 'subject') ? $direction : 'ASC']) }}">질문</a>
                        </th>
                        <th scope="col">
                            <a href="{{ Request::fullUrlWithQuery(['sst' => 'user_name', 'sod' => (Request::get('sst') == 'user_name') ? $direction : 'ASC']) }}">회원정보</a>
                        </th>
                        <th scope="col">
                            <a href="{{ Request::fullUrlWithQuery(['sst' => 'status', 'sod' => (Request::get('sst') == 'status') ? $direction : 'ASC']) }}">상태</a>
                        </th>
                        <th scope="col">
                            <a href="{{ Request::fullUrlWithQuery(['sst' => 'created_at', 'sod' => (Request::get('sst') == 'created_at') ? $direction : 'ASC']) }}">등록일</a>
                        </th>
                        <th scope="col">
                            <a href="{{ Request::fullUrlWithQuery(['sst' => 'answer_date', 'sod' => (Request::get('sst') == 'answer_date') ? $direction : 'ASC']) }}">답변 등록일</a>
                        </th>
                        <th scope="col">관리</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($qnas as $qna)
                        <tr class="bg{{ (int)$loop->even }}">
                            <td class="td_chk">
                                <label class="sound_only" for="chk_{{ $qna->id }}">{{ $qna->subject }}</label>
                                <input type="checkbox" id="chk_{{ $qna->id }}" name="chk[]" value="{{ $qna->id }}">
                            </td>
                            <td class="td_category" style="width:150px;">
                                {{ $qna->category->subject }}
                            </td>
                            <td class="td_left">
                                <a href="#" target="{{ $qna->id }}" class="qa_href" onclick="return false;">
                                    {{ $qna->subject }}
                                    <span class="tit_op">열기</span>
                                </a>
                                <div id="qa_div{{ $qna->id }}" class="qa_div" style="display: none;">
                                    <div class="qa_q">
                                        <strong>문의내용</strong>
                                        <p>{!! Purifier::clean($qna->content) !!}</p>
                                    </div>
                                    <div class="qa_a">
                                        <strong>답변</strong>
                                        <p>{!! Purifier::clean($qna->anwser_content) !!}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="td_admin_menu">
                                <div><x-content.user_side_view :user="$qna->user"/></div>
                            </td>
                            <td class="td_type">
                                @switch($qna->status)
                                    @case(1)
                                        <span style="color:orange">진행중</span>
                                        @break
                                    @case(2)
                                        <span style="color:darkgreen">답변완료</span>  
                                        @break
                                    @default
                                        <span style="color:red">접수</span>
                                @endswitch
                            </td>
                            <td class="td_datetime">{{ $qna->created_at }}</td>
                            <td class="td_datetime">{{ $qna->answer_date }}</td>
                            <td class="td_mng td_mng_s">
                                <a href="{{ route('admin.qna.edit', ['qna' => $qna]) . '?' . Request::getQueryString() }}"class="btn btn_03">
                                    <span class="sound_only">{{ $qna->subject }} </span>수정
                                </a>
                            </td>
                        </tr>
                    @empty
                        <td colspan="8" class="empty_table"><span>자료가 없습니다.</span></td>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="btn_fixed_top">
            <input type="submit" name="act_button" class="btn btn_02" value="선택삭제" onclick="document.pressed=this.value">
        </div>
    </form>

    {{ $qnas->links('paginate.admin.default', ['onEachSide' => $configDefault->cf_write_pages ?: 10]) }}

    <script>
        function fitemqalist_submit(f) {
            if (!is_checked("chk[]")) {
                alert(document.pressed + " 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            if (document.pressed == "선택삭제") {
                if (!confirm("선택한 자료를 정말 삭제하시겠습니까?\n첨부된 파일도 함께 삭제됩니다.")) {
                    return false;
                }
            }

            return true;
        }

        $(function() {
            $(".qa_href").click(function() {
                var $content = $("#qa_div" + $(this).attr("target"));
                $(".qa_div").each(function(index, value) {
                    if ($(this).get(0) == $content.get(0)) { // 객체의 비교시 .get(0) 를 사용한다.
                        $(this).is(":hidden") ? $(this).show() : $(this).hide();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>

</x-layout-admin>
