<x-layout :title="$qnaConfig->title">
    <x-slot:css>
        <link rel="stylesheet" href="{{ asset('css/theme/basic/qna/default.css') }}">
    </x-slot:css>
    <x-slot:script>
        <script src="{{ asset('/js/viewimageresize.js') }}"></script>
    </x-slot:script>

    <h2 id="container_title"><span title="{{ $qnaConfig->title }}">{{ $qnaConfig->title }}</span></h2>

    <x-qna::head :qnaConfig="$qnaConfig"/>

    <article id="bo_v">
        <header>
            <h2 id="bo_v_title">
                @if ($qna->category)
                    <span class="bo_v_cate">{{ $qna->category->subject }}</span>
                @endif
                {{ $qna->subject }}
            </h2>
        </header>

        <section id="bo_v_info">
            <h2>페이지 정보</h2>
            <span class="sound_only">작성자</span><strong> {{ $qna->user_name }}</strong>
            <span class="sound_only">작성일</span><strong class="bo_date"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $qna->created_at }}</strong>
            @if ($qna->user_email)
                <span class="sound_only">이메일</span><strong><i class="fa fa-envelope-o" aria-hidden="true"></i> {{ $qna->user_email }}</strong>
            @endif
            @if ($qna->user_hp)
                <span class="sound_only">휴대폰</span><strong><i class="fa fa-phone" aria-hidden="true"></i> {{ $qna->user_hp }}</strong>
            @endif

            <div id="bo_v_top">
                <ul class="bo_v_com">
                    <li>
                        <a href="{{ route('qna.index') . '?' . Request::getQueryString() }}" class="btn_b01 btn" title="목록">
                            <i class="fa fa-list" aria-hidden="true"></i><span class="sound_only">목록</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('qna.create') }}" class="btn_b01 btn" title="글쓰기">
                            <i class="fa fa-pencil" aria-hidden="true"></i><span class="sound_only">글쓰기</span>
                        </a>
                    </li>
                    <li>
                        <button type="button" class="btn_more_opt btn_b01 btn" title="게시판 읽기 옵션">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i><span class="sound_only">게시판 읽기 옵션</span>
                        </button>
                        <ul class="more_opt">
                            <li>
                                <a href="{{ route('qna.edit', ['qna' => $qna]) }}" class="btn_b01 btn" title="수정">
                                    수정<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:delete_qna_confirm(this)" class="btn_b01 btn" title="삭제"
                                    data-action="{{ route('qna.destroy', ['qna' => $qna]) }}">
                                    삭제<i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <script>
                    $(".btn_more_opt").on("click", function() {
                        $(".more_opt").toggle();
                    })
                </script>
            </div>
            <form id="delete_qna_form" name="delete_qna_form" method="POST" action="">
                @csrf
                @method('DELETE')
            </form>
        </section>

        <section id="bo_v_atc">
            <h2 id="bo_v_atc_title">본문</h2>
            <div id="bo_v_con">{!! Purifier::clean($qna->content) !!}</div>
            <div id="bo_v_img">
            @foreach ($qnaFiles->images as $file)
                    <a href="/" target="_blank" class="view_image">
                        <img src="{{ asset(Storage::url($file->path)) }}" alt="" title="{{ $file->file_name }}" width="{{ $file->width }}">
                    </a>
            @endforeach
            </div>
            <section id="bo_v_file">
                <h2>첨부파일</h2>
                <ul>
                    @foreach ($qnaFiles->files as $file)
                        <li>
                            <i class="fa fa-download" aria-hidden="true"></i>
                            <a href="{{ asset(Storage::url($file->path)) }}" class="view_file_download" download="{{ $file->file_name }}">
                                <strong>{{ $file->file_name }}</strong>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        </section>



        @if ($qna->status == 2)
            <section id="bo_v_ans">
                <h2><span class="bo_v_reply">답변</span> {{ $qna->answer_subject }}</h2>
                <header>
                    <div id="ans_datetime">
                        <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $qna->answer_date }}
                    </div>
                </header>
                <div id="ans_con">{!! Purifier::clean($qna->answer_content) !!}</div>
            </section>
        @else
            <section id="bo_v_ans_form">
                <p id="ans_msg">고객님의 문의에 대한 답변을 준비 중입니다.</p>
            </section>
        @endisset

        @if ($qna->status)
            <div class="bo_v_btn">
                <a href="{{ route('qna.create', ['related_qna_id' => $qna->id]) }}" class="add_qa" title="추가질문">추가질문</a>
            </div>
        @endif

    </article>

    <x-qna::tail :qnaConfig="$qnaConfig"/>

    <script>
        $(function() {
            $("a.view_image").click(function() {
                window.open(this.href, "large_image",
                    "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no"
                    );
                return false;
            });

            // 이미지 리사이즈
            $("#bo_v_atc").viewimageresize();
        });

        // 삭제 검사 확인
        function delete_qna_confirm(el) {
            if (confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
                $("#delete_qna_form").attr('action', $(el).data('action'));
                $("#delete_qna_form").submit();
                return true;
            } else {
                return false;
            }
        }
    </script>

</x-layout>
