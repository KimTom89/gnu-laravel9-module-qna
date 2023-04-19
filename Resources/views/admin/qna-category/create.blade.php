<x-layout-admin-popup :title="'Q&A 카테고리 추가'">
    <div id="menu_frm" class="new_win">
        <h1>Q&A 카테고리 추가</h1>
        <form id="qna_category_form" name="qna_category_form" class="new_win_con" action="{{ route('admin.qna-category.store') }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $qnaCategory->id }}">
            <div id="menu_result">
                <div class="tbl_frm01 tbl_wrap">
                    <table>
                        <colgroup>
                            <col class="grid_2">
                            <col>
                        </colgroup>
                        <tbody>
                            @isset ($qnaCategory->id)
                            <tr>
                                <th scope="row"><label for="label">상위카테고리</label></th>
                                <td>{{ $qnaCategory->subject }}</td>
                            </tr>
                            @endisset
                            <tr>
                                <th scope="row"><label for="subject">제목<strong class="sound_only"> 필수</strong></label></th>
                                <td><input type="text" id="subject" name="subject" class="frm_input required" size="50" required></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="link">사용여부</label></th>
                                <td>
                                    <input type="checkbox" id="is_use" name="is_use" value="1" checked>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="btn_win02 btn_win">
                    <button type="submit" class="btn_submit btn">추가</button>
                    <button type="button" class="btn_02 btn" onclick="window.close();">창닫기</button>
                </div>
            </div>
        </form>
    </div>
</x-layout-admin-popup>
