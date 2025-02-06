<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }
    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aCate            = _elm( $pageDatas, 'aCate', [] );


?>
<link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css">

 <!-- Toast UI Editor JS -->

<!-- 토스트 UI 에디터 코어 -->
<script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css" />
<link rel="stylesheet" href="https://nhn.github.io/tui.editor/latest/dist/cdn/theme/toastui-editor-dark.css">

<!-- 토스트 UI 컬러피커 -->
<link rel="stylesheet" href="https://uicdn.toast.com/tui-color-picker/latest/tui-color-picker.css" />
<script src="https://uicdn.toast.com/tui-color-picker/latest/tui-color-picker.min.js"></script>

<!-- 토스트 UI 컬러피커와 에디터 연동 플러그인 -->
<link rel="stylesheet" href="https://uicdn.toast.com/editor-plugin-color-syntax/latest/toastui-editor-plugin-color-syntax.min.css" />
<script src="https://uicdn.toast.com/editor-plugin-color-syntax/latest/toastui-editor-plugin-color-syntax.min.js"></script>

<!-- 토스트 UI 에디터 플러그인, 코드 신텍스 하이라이터 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.28.0/themes/prism-okaidia.min.css">
<link rel="stylesheet" href="https://uicdn.toast.com/editor-plugin-code-syntax-highlight/latest/toastui-editor-plugin-code-syntax-highlight.min.css">
<script src="https://uicdn.toast.com/editor-plugin-code-syntax-highlight/latest/toastui-editor-plugin-code-syntax-highlight-all.min.js"></script>

<!-- 토스트 UI 에디터 플러그인, 테이블 셀 병합 -->
<script src="https://uicdn.toast.com/editor-plugin-table-merged-cell/latest/toastui-editor-plugin-table-merged-cell.min.js"></script>

<!-- 토스트 UI 에디터 플러그인, UML -->
<script src="https://uicdn.toast.com/editor-plugin-uml/latest/toastui-editor-plugin-uml.min.js"></script>

<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">FAQ 목록</h3>
    </div>

    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card accordion-card"
                    style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                                viewBox="0 0 4 4" fill="none">
                                <circle cx="2" cy="2" r="2" fill="#206BC4" />
                            </svg>
                            <p class="body1-c ms-2 mt-1">
                                검색하기
                            </p>
                        </div>
                        <!-- 아코디언 토글 버튼 -->
                        <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
                            <span class="form-selectgroup-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8"
                                    viewBox="0 0 14 8" fill="none">
                                    <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="card-body">

                    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_search', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                    <input type="hidden" name="page" id="page" value="1">

                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <tbody>
                                <colgroup>
                                    <col style="width:10%;">
                                    <col style="width:40%;">
                                    <col style="width:10%;">
                                    <col style="width:50%;">
                                </colgroup>
                                <tr>
                                    <th class="no-border-bottom">검색</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <div class="form-inline">
                                            <?php
                                                $options = ['title'=>'제목','content'=>'내용', 'answer'=>'답변' ];
                                                $extras   = ['id' => 's_condition', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                                                $selected = '';
                                                echo getSelectBox('s_condition', $options, $selected, $extras);
                                            ?>
                                            <input type="text" class="form-control" style="width:15.2rem" name="s_keyword" id="s_keyword">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">분류</th>
                                    <td colspan="3" class="no-border-bottom">
                                        <?php
                                            $options  = [ '' => '전체'];
                                            $options += $aCate;
                                            $extras   = ['id' => 's_cate', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                                            $selected = '';
                                            echo getSelectBox('s_cate', $options, $selected, $extras);
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th class="no-border-bottom">상태</th>
                                    <td colspan="3" class="no-border-bottom">
                                    <?php
                                        $options = [''=>'전체', 'Y'=>'노출', 'N'=>'비노출','R'=>'승인대기', 'D'=>'삭제'];
                                        $extras   = ['id' => 's_status', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                                        $selected = '1';
                                        echo getSelectBox('s_status', $options, $selected, $extras);
                                    ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom" style="padding-bottom:0;">Best</th>
                                    <td colspan="3" class="no-border-bottom" style="padding-bottom:0;">
                                        <div class="input-group" style="padding-top:0.45rem;">
                                        <?php
                                            $checked = false;

                                            $setParam = [
                                                'name' => 's_is_best',
                                                'id' => 's_is_best_Y',
                                                'value' => 'Y',
                                                'label' => '베스트',
                                                'checked' => $checked,
                                                'extraAttributes' => [
                                                    'aria-label' => 'Single checkbox One',
                                                    'class'=>'check-item',
                                                ]
                                            ];
                                            echo getCheckBox( $setParam );
                                        ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php echo form_close();?>
                    <div style="text-align: center; margin-top: 36px">
                    <?php
                    echo getIconButton([
                        'txt' => '검색',
                        'icon' => 'search',
                        'buttonClass' => 'btn text-white',
                        'buttonStyle' => 'width: 180px; height: 46px;background-color:#206BC4',
                        'width' => '21',
                        'height' => '20',
                        'stroke' => 'white',
                        'extra' => [
                            'onclick' => 'getSearchList();',
                        ]
                    ]);
                    ?>
                    <?php
                    echo getIconButton([
                        'txt' => '초기화',
                        'icon' => 'reset',
                        'buttonClass' => 'btn',
                        'buttonStyle' => 'width: 180px; height: 46px',
                        'width' => '21',
                        'height' => '20',
                        'extra' => [
                            'onclick' => 'document.location.reload()',
                        ]
                    ]);
                    ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<br>
<div class="container-fluid">
    <div class="col-12 col">
        <!-- 카드 타이틀 -->
        <div class="card accordion-card" style="padding: 17px 24px; border-radius: 4px 4px 0 0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                        fill="none">
                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                    </svg>
                    <p class="body1-c ms-2 mt-1">
                        목록
                    </p>
                </div>
                <!-- 아코디언 토글 버튼 -->
                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
                    <span class="form-selectgroup-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8"
                            viewBox="0 0 14 8" fill="none">
                            <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </label>
            </div>
        </div>

        <div class="card accordion-card" style="padding: 17px 24px; border-top:0">
            <div class="form-inline" style="justify-content: space-between;width: 100%;">
                <!-- 버튼 그룹 왼쪽정렬-->
                <div id="buttons">
                </div>

                <!-- 버튼 그룹 오른쪽정렬-->
                <div>
                <?php
                    echo getIconButton([
                        'txt' => '등록',
                        'icon' => 'add',
                        'buttonClass' => 'btn ',
                        'buttonStyle' => 'width: 100px; height: 34px',
                        'width' => '20',
                        'height' => '20',
                        'stroke' => '#1D273B',
                        'extra' => [
                            'onclick' => "openLayer('', 'dataModal')",
                        ]
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- 테이블 -->
            <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_lists',  'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
            <div style="border:1px solid #E6E7E9; border-top: 0px; border-radius:0 0 4px 4px">
                <div class="table-responsive">
                    <table class="table table-vcenter" id="listsTable">
                        <colgroup>
                            <col style="width:5%;">
                            <col style="width:5%;">
                            <col style="width:20%;">
                            <col style="width:25%;">
                            <col style="width:10%;">
                            <col style="width:10%;">
                            <col style="width:5%;">
                            <col style="width:10%;">
                            <col style="width:10%;">
                            <col style="width:5%;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>
                                    <div class="checkbox checkbox-single">
                                        <?php
                                        $setParam = [
                                            'name' => '',
                                            'id' => 'checkAll',
                                            'value' => '',
                                            'label' => '',
                                            'checked' => false,
                                            'extraAttributes' => [
                                                'class'=>'checkAll',
                                                'aria-label' => 'Single checkbox One'
                                            ]
                                        ];
                                        echo getCheckBox( $setParam );
                                        ?>
                                    </div>
                                </th>
                                <th>번호</th>
                                <th>분류</th>
                                <th>제목</th>
                                <th>상태</th>
                                <th>첨부파일</th>
                                <th>작성자</th>
                                <th>등록일</th>
                                <th>수정일</th>
                                <th>조회수</th>
                                <th>삭제</th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
                <!--페이징-->
                <div class="pagination-wrapper" id="pagination">


                </div>
            </div>
            <?php echo form_close()?>
        </div>
    </div>
</div>
<!-- Modal S-->
<div class="modal fade" id="dataModal" tabindex="-1" style="margin-top:3em;" style="z-index:auto" aria-labelledby="dataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:80vh">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModalLabel">등록/수정</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
                <div class="viewData">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal E-->
<script>
var editorInstances = [];

function openLayer( faqIdx, id ){
    let data = '';
    let url  = '/apis/board/faqRegister';
    var editor_ids = ['contents_editor', 'answer_editor'];

    if( faqIdx != '' ){
        data = 'i_idx='+faqIdx;
        url  = '/apis/board/faqDetail';
    }
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        processData: false,
        cache: false,
        beforeSend: function() { },
        success: function(response) {
            submitSuccess(response);

            if (response.status == 'false')
            {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }

                return false;
            }
            $('#'+id+' .viewData').empty().html( response.page_datas.detail );
            editor_ids.forEach(function(editor_id) {
                initializeToastUIEditor(editor_id);
            });
            //var modal = new bootstrap.Modal(document.getElementById(id));
            var modalElement = document.getElementById(id);
            var modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static', // 마스크 클릭해도 닫히지 않게 설정
                keyboard: false     // esc 키로 닫히지 않게 설정
            });

            modal.show();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);

            return false;
        },
        complete: function() { }
    });
}



    function initializeToastUIEditor( $id ) {
         // editor-container의 너비를 가져와서 80% 계산

        var editorElement = document.querySelector('#'+$id);

        editorInstances[$id] = new toastui.Editor({
            el: document.querySelector('#'+$id),

            height: '250px',
            initialEditType: 'wysiwyg',
            previewStyle: 'vertical',
            toolbarItems: [
                ['heading', 'bold', 'italic', 'strike'],
                ['hr', 'quote'],
                ['ul', 'ol', 'task'],
                ['table', 'link'],
                ['image'],
                ['indent', 'outdent'],
                ['scrollSync'],
                // [
                //     {
                //         name: 'alignleft',
                //         text: '왼쪽 정렬',
                //         tooltip: '왼쪽 정렬',
                //         style: { 'margin-right': '4px' },
                //         className: alignLeftClass
                //     },
                //     {
                //         name: 'aligncenter',
                //         text: '가운데 정렬',
                //         tooltip: '가운데 정렬',
                //         style: { 'margin-right': '4px' },
                //         className: alignCenterClass
                //     },
                //     {
                //         name: 'alignright',
                //         text: '오른쪽 정렬',
                //         tooltip: '오른쪽 정렬',
                //         style: { 'margin-right': '4px' },
                //         className: alignRightClass
                //     }
                // ]
            ],
            hooks: {
                addImageBlobHook: function(blob, callback) {
                    var reader = new FileReader();
                    reader.onload = function() {
                        var base64Image = reader.result.split(',')[1];
                            $.ajax({
                                url: '/apis/design/writeImage',
                                method: 'POST',
                                contentType: 'application/json',
                                data: JSON.stringify({
                                image: base64Image,
                                path: 'goods/editor'
                            }),
                            success: function(response) {
                                var imageUrl = response.url;
                                callback(imageUrl, 'alt text');
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('Error uploading file:', textStatus, errorThrown);
                            }
                        });
                    };
                    reader.readAsDataURL(blob);
                }
            }
        });
        if( $id == 'contents_editor' ){
            editorInstances[$id].setMarkdown(contents_editor_value || '');
        }else{
            editorInstances[$id].setMarkdown(answer_editor_value || '');
        }



        editorElement.style.width = '660px';

    }
    // contents_editor 초기화
</script>

<?php
$owensView->setFooterJs('/assets/js/board/faq/lists.js');
$owensView->setFooterJs('/assets/js/board/faq/register.js');
$owensView->setFooterJs('/assets/js/board/faq/detail.js');

$script = "
";

$owensView->setFooterScript($script);

