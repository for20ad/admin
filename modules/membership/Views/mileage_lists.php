<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }
    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aConfig          = _elm( $pageDatas, 'aConfig', [] );


    $aMemberLists    = _elm( $pageDatas, 'member_lists', [] );
    $aMemberGrade    = _elm( $pageDatas, 'member_grade', [] );

    $aGetData        = _elm( $pageDatas, 'getData', [] );
    $aWaitMembers    = _elm( $pageDatas, 'wait_members', 0 );
    $aFormLists      = _elm( $pageDatas, 'form_lists', [] );

?>
<link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>

<link rel="stylesheet" href="/plugins/common/mCustomScrollbar-min.css" integrity="sha512-6qkvBbDyl5TDJtNJiC8foyEVuB6gxMBkrKy67XpqnIDxyvLLPJzmTjAj1dRJfNdmXWqD10VbJoeN4pOQqDwvRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="/plugins/common/mCustomScrollbar.js"></script>

<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">회원 목록</h3>
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
                    <input type="hidden" name="page" id="page" value="<?php echo _elm( $aGetData, 'page' );?>">
                    <input type="hidden" name="getData" id="getData" value="<?php $aGetData;?>">
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
                                                $options = [''=>'전체'];
                                                $options+= ['mb_id'=>'아이디', 'mb_name'=>'이름', 'mb_mobile'=>'휴대폰번호'];
                                                $extras   = ['id' => 's_condition', 'class' => 'form-select', 'style' => 'max-width: 174px;margin-right:0.235em;'];
                                                $selected = _elm( $aGetData, 's_condition' );
                                                echo getSelectBox('s_condition', $options, $selected, $extras);
                                            ?>
                                            <input type="text" id="ssubject" name="s_keyword" class="form-control" style="width:20%" value="<?php echo _elm( $aGetData, 's_keyword' )?>" >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">상태</th>
                                    <td class="no-border-bottom" colspan='3'>
                                        <?php
                                            $options  = _elm($aConfig, 'status', []);
                                            $extras   = ['id' => 's_status', 'class' => 'form-select', 'style' => 'max-width: 174px'];
                                            $selected = 2;
                                            echo getSelectBox('s_status', $options, $selected, $extras);
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div style="text-align: center; margin-top: 15px">
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
                        회원포인트목록
                    </p>
                </div>
                <div style="display: flex">
                    <div style="padding-right:2.6rem">
                    <?php
                        $options = ['20'=>'20개씩 보기', '30'=>'30개씩 보기', '50'=>'50개씩 보기', '100'=>'100개씩 보기', '200'=>'200개씩 보기'];
                        $extras   = ['id' => 'per_page', 'class' => 'form-select', 'style' => 'max-width: 174px;margin-right:0.235em;', 'onchange'=>'getSearchList()'];
                        $selected = _elm( $aGetData, 'per_page' );
                        echo getSelectBox('per_page', $options, $selected, $extras);
                    ?>
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
        </div>
        <?php echo form_close();?>
        <div class="card accordion-card" style="padding: 17px 24px; border-top:0">
            <div class="form-inline" style="justify-content: space-between;width: 100%;">
                <div>

                </div>
                <div>
                <?php
                echo getIconButton([
                    'txt' => '포인트지급',
                    'icon' => 'add',
                    'buttonClass' => 'btn ',
                    'buttonStyle' => 'width: 160px; height: 34px',
                    'width' => '20',
                    'height' => '20',
                    'stroke' => '#1D273B',
                    'extra' => [
                        'onclick' => 'openMileageLayer("", "mileageModal");',
                    ]
                ]);
                ?>
                <?php
                echo getIconButton([
                    'txt' => '엑셀다운로드',
                    'icon' => 'download',
                    'buttonClass' => 'btn ',
                    'buttonStyle' => 'width: 160px; height: 34px',
                    'width' => '20',
                    'height' => '20',
                    'stroke' => '#1D273B',
                    'extra' => [
                        'onclick' => 'excelDownloadConfirm("formListModal");',
                    ]
                ]);
                ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- 테이블 -->
            <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_lists',  'onSubmit' => 'modifyGradeConfirm(); eturn false;', 'autocomplete' => 'off']); ?>
            <div style="border:1px solid #E6E7E9; border-top: 0px; border-radius:0 0 4px 4px">
                <div class="table-responsive">
                    <table class="table table-vcenter" id="listsTable">
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
                                <th>No.</th>
                                <th>아이디</th>
                                <th>이름</th>
                                <th>적립</th>
                                <th>사용</th>
                                <th>차감</th>
                                <th>소멸</th>
                                <th>잔고</th>
                                <th>지급/회수</th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
                <!--페이징-->
                <div class="pagination-wrapper" id="paginatoon">


                </div>
            </div>
        </div>
        <?php echo form_close()?>
        </div>
    </div>
</div>
<!-- Modal S-->
<div class="modal fade" id="mileageModal" tabindex="-1" style="margin-top:3em;" aria-labelledby="mileageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:80vh">
            <div class="modal-header">
                <h5 class="modal-title" id="memberModalLabel">포인트</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetLoopScrollParams()"></button>
            </div>
            <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
                <div class="viewData">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal E-->
<!-- MemberModal S-->
<div class="modal fade" id="memberModal" tabindex="-1" style="margin-top:3em;" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="max-height:90vh;display:flex;flex-direction:column;width:80vh">
                <div class="modal-header">
                    <h5 class="modal-title" id="memberModalLabel">포인트 내역</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
                    <div class="row row-deck row-cards">
                        <!-- 카드1 -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive fixTable">
                                        <table class="table table-vcenter">
                                            <colgroup>
                                                <col style="width:10%;">
                                                <col style="width:15%;">
                                                <col style="width:15%;">
                                                <col style="width:15%;">
                                                <col style="width:15%;">
                                                <col style="width:30%;">
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th class="fixth" >No.</th>
                                                    <th class="fixth" >지급액</th>
                                                    <th class="fixth" >차감액</th>
                                                    <th class="fixth" >잔액</th>
                                                    <th class="fixth" >처리자</th>
                                                    <th class="fixth" >사유</th>
                                                </tr>
                                            </thead>
                                            <tbody id="viewData">
                                                <!-- 데이터 행이 여기에 추가됩니다 -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal E-->
<!-- Form List Modal S-->
<div class="modal fade" id="formListModal" tabindex="-1" aria-labelledby="formListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;">
            <div class="modal-header">
                <h5 class="modal-title" id="formListModalLabel">폼 리스트</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
                <ul class="list-group">
                    <?php foreach ($aFormLists as $fkey => $form){ ?>
                        <label class="form-check-label" for="s_form_idx_<?php echo _elm($form, 'F_IDX'); ?>">
                            <li class="list-group-item d-flex align-items-center" style="padding-left:2.5rem;margin-bottom:0.2em">
                                <input class="form-check-input me-2" type="radio" name="s_form_idx" id="s_form_idx_<?php echo _elm($form, 'F_IDX'); ?>" value="<?php echo _elm($form, 'F_IDX'); ?>">
                                <div class="h4-c" style="padding-left:1.5rem"><?php echo _elm($form, 'F_TITLE'); ?></div>
                            </li>
                        </label>
                    <?php } ?>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                <button type="button" class="btn btn-primary" onclick="selectFormAndDownload()">선택</button>
            </div>
        </div>
    </div>
</div>
<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'excelForm', 'autocomplete' => 'off']);?>
<input type="hidden" name="s_condition" value="">
<input type="hidden" name="s_keyword" value="">
<input type="hidden" name="s_status" value="">
<input type="hidden" name="s_form_idx" value="">
<?php echo form_close()?>
<!-- Form List Modal E-->
<script>

document.addEventListener('DOMContentLoaded', function() {
    let thumbHeight = 25; // 원하는 썸 높이
    let style = document.createElement('style');
    style.innerHTML = `
        ::-webkit-scrollbar-thumb {
            height: ${thumbHeight}px !important;
        }
    `;
    document.head.appendChild(style);
});
</script>

<?php
$owensView->setFooterJs('/assets/js/membership/mileage_lists.js');
$owensView->setFooterJs('/assets/js/membership/mileage_detail.js');
$owensView->setFooterJs('/assets/js/membership/mileage_register.js');

$script = "

";

$owensView->setFooterScript($script);

