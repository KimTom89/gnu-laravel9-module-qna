<x-layout :title="$qnaConfig->title">
    <x-slot:css>
        <link rel="stylesheet" href="{{ asset('css/skin/qna/default.css') }}">
    </x-slot:css>

    <h2 id="container_title"><span title="{{ $qnaConfig->title }}">{{ $qnaConfig->title }}</span></h2>

    <x-qna::head :qnaConfig="$qnaConfig"/>

    <div id="bo_list">
        <nav class="bo_cate">
            <h2>{{ $qnaConfig->title }} 카테고리</h2>
            <ul id="bo_cate_ul">
                <li><a href="{{ route('qna.index') }}" @class(['bo_cate_on' => Request::get('category') == null])>전체</a></li>
                @foreach ($qnaCategories as $category)
                    <li>
                        <a href="{{ route('qna.index', ['category' => $category->id]) }}" @class(['bo_cate_on' => Request::get('category') == $category->id])>
                            {{ $category->subject }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
        <div id="bo_btn_top">
            <div id="bo_list_total">
                <span>Total {{ $total }}건</span>
                {{ $qnas->currentPage() }} 페이지
            </div>
            <ul class="btn_bo_user">
                @adminPage
                    <li>
                        <a href="{{ route('admin.qna-config.index') }}" class="btn_admin btn" title="관리자">
                            <i class="fa fa-cog fa-spin fa-fw"></i>
                            <span class="sound_only">관리자</span>
                        </a>
                    </li>
                @endadminPage
                <li>
                    <button type="button" class="btn_bo_sch btn_b01 btn" title="게시판 검색">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <span class="sound_only">게시판 검색</span>
                    </button>
                    <div class="bo_sch_wrap">
                        <fieldset class="bo_sch">
                            <h3>검색</h3>
                            <legend>게시물 검색</legend>
                            <form name="fsearch" method="GET">
                                <input type="hidden" name="category" value="{{ Request::get('category') }}">
                                <label class="sound_only" for="sfl">검색대상</label>
                                <select id="sfl" name="sfl">
                                    <option value="qnas.subject">제목</option>
                                    <option value="qnas.content">내용</option>
                                    <option value="qnas.user_name">글쓴이</option>
                                </select>
                                <label class="sound_only" for="stx">검색어<strong class="sound_only"> 필수</strong></label>
                                <div class="sch_bar">
                                    <input type="text" id="stx" name="stx" class="sch_input" value="{{ Request::get('stx') }}" required
                                        size="25" maxlength="15" placeholder="검색어를 입력해주세요">
                                    <button type="submit" class="sch_btn" title="검색" value="검색">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        <span class="sound_only">검색</span>
                                    </button>
                                </div>
                                <button type="button" class="bo_sch_cls">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                    <span class="sound_only">닫기</span>
                                </button>
                            </form>
                        </fieldset>
                        <div class="bo_sch_bg"></div>
                    </div>
                    <script>
                        $(".btn_bo_sch").on("click", function() {
                            $(".bo_sch_wrap").toggle();
                        })
                        $('.bo_sch_bg, .bo_sch_cls').click(function() {
                            $('.bo_sch_wrap').hide();
                        });
                    </script>
                </li>
                <li>
                    <a href="{{ route('qna.create') }}" class="btn_b01 btn" title="문의등록">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        <span class="sound_only">문의등록</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="tbl_head01 tbl_wrap">
            <table>
                <caption>목록</caption>
                <thead>
                    <tr>
                        <th scope="col">번호</th>
                        <th scope="col">제목</th>
                        <th scope="col">글쓴이</th>
                        <th scope="col">등록일</th>
                        <th scope="col">상태</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($qnas as $qna)
                        <tr @class(['even' => $loop->even])>
                            <td class="td_num">{{ $qna->listNumber }}</td>
                            <td class="td_subject">
                                @isset ($qna->category->subject)
                                    <span class="bo_cate_link">{{ $qna->category->subject }}</span>
                                @endisset
                                <a href="{{ route('qna.show', ['qna' => $qna]) . '?' . Request::getQueryString() }}" class="bo_tit">
                                    {{ $qna->subject }}
                                    @if ($qna->files->count())
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                    @endif
                                </a>
                            </td>
                            <td class="td_name">{{ $qna->user->nick }}</td>
                            <td class="td_date">{{ $qna->created_at }}</td>
                            <td class="td_stat">
                                @switch($qna->status)
                                    @case(1)
                                        <span class="txt_rdy">처리 중</span>
                                        @break
                                    @case(2)
                                        <span class="txt_done">답변완료</span>   
                                        @break
                                    @default
                                        <span class="txt_rdy">접수</span>
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty_table" colspan="7">게시물이 없습니다.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $qnas->links('paginate.board_list', ['onEachSide' => Utils::isMobile() ? $configDefault->cf_mobile_pages : $configDefault->cf_write_pages]) }}
    </div>

    <x-qna::tail :qnaConfig="$qnaConfig"/>

</x-layout>
