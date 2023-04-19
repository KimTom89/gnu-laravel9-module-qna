<x-layout-admin :title="'Q&A 카테고리 관리'">
    <div class="local_desc01 local_desc">
        <p>
            <strong>주의!</strong> 카테고리 설정 작업 후 반드시 <strong>확인</strong>을 누르셔야 저장됩니다. <br>
            현재 상위카테고리만 사용 가능합니다.
        </p>
    </div>
    <form id="form_qna_category" name="form_qna_category" method="POST" action="{{ route('admin.qna-category.list.update') }}" onsubmit="return form_qna_category_submit(this);">
        @csrf
        @method('PUT')
        <div id="menulist" class="tbl_head01 tbl_wrap">
            <table>
                <caption>1:1문의 카테고리 목록</caption>
                <thead>
                    <tr>
                        <th scope="col" width="70%">제목</th>
                        <th scope="col">순서</th>
                        <th scope="col">사용여부</th>
                        <th scope="col">등록일시</th>
                        <th scope="col">관리</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($qnaCategories as $category)
                        <tr class="bg1 menu_list menu_group_{{ $category->id }}">
                            <td class="td_admin_menu">
                                <input type="hidden" name="id[]" value="{{ $category->id }}">
                                <label class="sound_only" for="subject_{{ $category->id }}">메뉴<strong class="sound_only"> 필수</strong></label>
                                <input type="text" id="subject_{{ $category->id }}" name="subject[{{ $category->id }}]" class="required tbl_input full_input"
                                    value="{{ $category->subject }}" required>
                            </td>
                            <td class="td_numbig">
                                <label class="sound_only" for="position_{{ $category->id }}">순서</label>
                                <input type="text" id="position_{{ $category->id }}" name="position[{{ $category->id }}]" class="tbl_input" value="{{ $category->position }}" size="5">
                            </td>
                            <td class="td_chk">
                                <label class="sound_only" for="is_use_{{ $category->id }}">사용여부</label>
                                <input type="checkbox" id="is_use_{{ $category->id }}" name="is_use[{{ $category->id }}]" value="1"
                                    @checked($category->is_use)>
                            </td>
                            <td class="td_datetime">
                                {{ $category->created_at }}
                            </td>
                            <td class="td_mng">
                                <button type="button" class="btn_add_subcategory btn_03" data-url="{{ route('admin.qna-category.create', ['qnaCategory' => $category]) }}">추가</button>
                                <button type="button" class="btn_del_category btn_02" data-url="{{ route('admin.qna-category.destroy', ['qnaCategory' => $category]) }}">삭제</button>
                            </td>
                        </tr>
                        
                        @foreach ($category->children as $child)
                            <tr class="bg0 menu_list menu_group_{{ $child->id }}">
                                <td class="sub_menu_class">
                                    <input type="hidden" name="id[]" value="{{ $child->id }}">
                                    <label class="sound_only" for="subject_{{ $child->id }}">메뉴<strong class="sound_only"> 필수</strong></label>
                                    <input type="text" id="subject_{{ $child->id }}" name="subject[{{ $child->id }}]" class="required tbl_input full_input"
                                        value="{{ $child->subject }}" required>
                                </td>
                                <td class="td_numbig">
                                    <label class="sound_only" for="position_{{ $child->id }}">순서</label>
                                    <input type="text" id="position_{{ $child->id }}" name="position[{{ $child->id }}]" class="tbl_input" value="{{ $child->position }}" size="5">
                                </td>
                                <td class="td_chk">
                                    <label class="sound_only" for="is_use_{{ $child->id }}">사용여부</label>
                                    <input type="checkbox" id="is_use_{{ $child->id }}" name="is_use[{{ $child->id }}]" value="1"
                                        @checked($child->is_use)>
                                </td>
                                <td class="td_datetime">
                                    {{ $child->created_at }}
                                </td>
                                <td class="td_mng">
                                    <button type="button" class="btn_del_category btn_02" data-url="{{ route('admin.qna-category.destroy', ['qnaCategory' => $child]) }}">삭제</button>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr id="empty_menu_list"><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="btn_fixed_top">
            <button type="button" class="btn btn_02 btn_add_category" data-url="{{ route('admin.qna-category.create') }}">
                카테고리추가<span class="sound_only"> 새창</span>
            </button>
            <input type="submit" name="act_button" class="btn_submit btn " value="확인">
        </div>

    </form>

    <script>
        $(function() {
            $(document).on("click", ".btn_add_category, .btn_add_subcategory", function() {
                var url = $(this).data('url');
                window.open(url, "add_category", "left=100,top=100,width=600,height=400,scrollbars=yes,resizable=yes");
                return false;
            });

            $(document).on("click", ".btn_del_category", function() {
                if (!confirm("카테고리를 삭제하시겠습니까?")) {
                    return false;
                }
                
                $('#form_qna_category').attr('action', $(this).data('url'));
                $('#form_qna_category input[name=_method]').val('DELETE');

                $('#form_qna_category').submit();
            });
        });

        function form_qna_category_submit(f) {
            return true;
        }
    </script>
</x-layout-admin>
