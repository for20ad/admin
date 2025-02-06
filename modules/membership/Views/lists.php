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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="/plugins/common/mCustomScrollbar-min.css"
    integrity="sha512-6qkvBbDyl5TDJtNJiC8foyEVuB6gxMBkrKy67XpqnIDxyvLLPJzmTjAj1dRJfNdmXWqD10VbJoeN4pOQqDwvRA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4" fill="none">
                                <circle cx="2" cy="2" r="2" fill="#206BC4" />
                            </svg>
                            <p class="body1-c ms-2 mt-1">
                                검색하기
                            </p>
                        </div>
                        <!-- 아코디언 토글 버튼 -->
                        <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
                            <span class="form-selectgroup-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8"
                                    fill="none">
                                    <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round" />
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
                                                $options  = ['mb_id'=>'아이디', 'mb_name'=>'이름', 'mb_mobile'=>'휴대폰번호'];
                                                $extras   = ['id' => 's_condition', 'class' => 'form-select', 'style' => 'max-width: 174px;margin-right:0.235em;'];
                                                $selected = _elm( $aGetData, 's_condition' );
                                                echo getSelectBox('s_condition', $options, $selected, $extras);
                                            ?>
                                            <input type="text" id="ssubject" name="s_keyword" class="form-control"
                                                style="width:20%" value="<?php echo _elm( $aGetData, 's_keyword' )?>">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">상태</th>
                                    <td class="no-border-bottom" colspan='3'>
                                        <?php
                                            $options  = _elm($aConfig, 'status', []);
                                            $extras   = ['id' => 's_status', 'class' => 'form-select', 'style' => 'max-width: 174px'];
                                            $selected = _elm( $aGetData, 's_status' );
                                            echo getSelectBox('s_status', $options, $selected, $extras);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">
                                        등급
                                    </th>
                                    <td class="no-border-bottom" colspan='3'>
                                        <?php
                                        $options = [''=>'전체'];
                                        $options+= $aMemberGrade??[];
                                        $extras   = ['id' => 's_grade', 'class' => 'form-select', 'style' => 'max-width: 174px'];
                                        $selected = _elm( $aGetData, 's_grade' );
                                        echo getSelectBox('s_grade', $options, $selected, $extras);
                                    ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">
                                        회원가입일
                                    </th>
                                    <td class="no-border-bottom" colspan='3'>
                                        <div class="form-inline">
                                            <div class="input-icon">
                                                <input type="text" class="form-control datepicker-icon"
                                                    name="s_start_date" readonly>
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <rect x="4" y="5" width="16" height="16" rx="2" />
                                                        <line x1="16" y1="3" x2="16" y2="7" />
                                                        <line x1="8" y1="3" x2="8" y2="7" />
                                                        <line x1="4" y1="11" x2="20" y2="11" />
                                                        <line x1="11" y1="15" x2="12" y2="15" />
                                                        <line x1="12" y1="15" x2="12" y2="18" />
                                                    </svg>
                                                </span>
                                            </div>
                                            ~
                                            <div class="input-icon">
                                                <input type="text" class="form-control datepicker-icon"
                                                    name="s_end_date" readonly>
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <rect x="4" y="5" width="16" height="16" rx="2" />
                                                        <line x1="16" y1="3" x2="16" y2="7" />
                                                        <line x1="8" y1="3" x2="8" y2="7" />
                                                        <line x1="4" y1="11" x2="20" y2="11" />
                                                        <line x1="11" y1="15" x2="12" y2="15" />
                                                        <line x1="12" y1="15" x2="12" y2="18" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
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
    <div class="col-12">
        <!-- 카드 타이틀 -->
        <div class="card accordion-card" style="padding: 17px 24px; border-radius: 4px 4px 0 0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4" fill="none">
                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                    </svg>
                    <p class="body1-c ms-2 mt-1">
                        회원목록 |
                        <span style="font-size:10pt">  액샐다운로드는 현재 검색된 리스트가 출력됩니다.</span>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8" fill="none">
                                <path d="M1 7L7 1L13 7" stroke="#616876" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </span>

                    </label>
                </div>
            </div>
        </div>
        <?php echo form_close();?>
        <div class="card accordion-card" style="padding: 17px 24px; border-top:0">
            <div class="form-inline" style="justify-content: space-between;width: 100%;">
                <!-- 버튼 그룹 왼쪽정렬-->
                <div id="buttons">
                    <?php if( $aWaitMembers > 0 ){?>
                    대기회원 <?php echo number_format($aWaitMembers)?> 명
                    <?php
                        echo getButton([
                            'text' => '보기',
                            'class' => 'btn btn-info',
                            'style' => 'width: 60px; height:34px',
                            'extra' => [
                                'onclick' => '$("#frm_search [name=s_status]").val("1");getSearchList()',
                                'name' => 'xBtn',
                            ]
                        ]);
                        ?>
                    |
                    <?php } ?>
                    <?php
                    echo getButton([
                        'text' => '선택대기',
                        'class' => 'btn btn-secondary',
                        'style' => 'width: 60px; height:34px',
                        'extra' => [
                            'onclick' => 'setWaitMembersConfirm()',
                        ]
                    ]);
                    ?>
                    <?php
                    echo getButton([
                        'text' => '선택승인',
                        'class' => 'btn btn-success',
                        'style' => 'width: 60px; height:34px',
                        'extra' => [
                            'onclick' => 'setApprovalMembersConfirm()',
                        ]
                    ]);
                    ?>
                </div>
                <!-- 버튼 그룹 오른쪽정렬-->
                <div>
                    <?php
                echo getIconButton([
                    'txt' => '회원등록',
                    'icon' => 'add',
                    'buttonClass' => 'btn ',
                    'buttonStyle' => 'width: 160px; height: 34px',
                    'width' => '20',
                    'height' => '20',
                    'stroke' => '#1D273B',
                    'extra' => [
                        'onclick' => 'openLayer("", "memberModal");',
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
            <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_lists',  'onSubmit' => 'modifyGradeConfirm(); return false;', 'autocomplete' => 'off']); ?>
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
                                <th>아이디</th>
                                <th>이름</th>
                                <th>등급</th>
                                <th>회원유형</th>
                                <th>포인트</th>
                                <th>주문건수</th>
                                <th>주문금액</th>
                                <th>상태</th>
                                <th>상담내역</th>
                                <th>등록일</th>
                                <th>최종접속일</th>
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
</div>
<!-- Modal S-->
<div class="modal fade" id="memberModal" tabindex="-1" style="margin-top:3em;" aria-labelledby="memberModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:80vh">
            <div class="modal-header">
                <h5 class="modal-title" id="memberModalLabel">정보 등록</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
                <div class="viewData">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="counselModal" tabindex="-1" style="margin-top:3em;z-index:9999" aria-labelledby="counselLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:100vh;display:flex;flex-direction: column;width:90vh">
            <div class="modal-header">
                <h5 class="modal-title" id="counselLabel">상담 내역</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
                <div class="viewData">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="dataModal" tabindex="-1" style="margin-top:3em;" aria-labelledby="dataModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:90vh">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModalLabel">회원 정보</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
                <div id="headerInfo" class="display: grid; grid-template-columns: 60% 40%; align-items: center;margin-top:-0.5rem">
                </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab1-tab" onclick="openLayer( show_mb_idx, 'dataModal', 'summery' );" data-bs-toggle="tab" data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">
                            요약보기
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab2-tab" onclick="openLayer( show_mb_idx, 'dataModal', 'info' );" data-bs-toggle="tab" data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false">
                            회원정보
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab3-tab" onclick="openLayer( show_mb_idx, 'dataModal', 'orderList' );" data-bs-toggle="tab" data-bs-target="#tab3" type="button" role="tab" aria-controls="tab3" aria-selected="false">
                            주문내역
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab4-tab" onclick="openLayer( show_mb_idx, 'dataModal', 'point' );" data-bs-toggle="tab" data-bs-target="#tab4" type="button" role="tab" aria-controls="tab4" aria-selected="false">
                            포인트내역
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab5-tab" onclick="openLayer( show_mb_idx, 'dataModal', 'coupon' );" data-bs-toggle="tab" data-bs-target="#tab5" type="button" role="tab" aria-controls="tab5" aria-selected="false">
                            쿠폰내역
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                        <div class="viewData">
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                        <div class="viewData">
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
                        <div class="viewData">
                        </div>
                    </div>
                    <div class="tab-pane fade show active" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
                        <div class="viewData">
                        </div>
                    </div>
                    <div class="tab-pane fade show active" id="tab5" role="tabpanel" aria-labelledby="tab5-tab">
                        <div class="viewData">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Modal E-->

<!-- 레이어 팝업 -->
<div id="layerPopup" style="display:none; top:50%; left:50%; transform:translate(-50%, -50%); width:400px; background-color:white; border:1px solid #ccc; z-index:999999999; padding:20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; flex-direction:column; justify-content:space-between;">

    <div>
        <h4 style="text-align:center; padding-bottom: 10px; border-bottom: 2px solid #eee; margin-bottom: 20px;">
            쿠폰 사용범위
        </h4>
        <p id="popupContent" style="padding-top:10px; flex-grow:1; margin-bottom: 40px;">여기에 쿠폰 내용을 표시합니다.</p>
    </div>

    <div style="position:relative;margin-top:1.2rem; height: 35px;">
        <?php
            echo getButton([
                'text' => '닫기',
                'class' => 'btn btn-secondary',
                'style' => 'width: 80px; height: 35px; font-size:12px; position:absolute; bottom:0; left:50%; transform:translateX(-50%);',
                'extra' => [
                    'onclick' => 'event.preventDefault();closeLayerPopup()',
                ]
            ]);
        ?>
    </div>
</div>
<!-- 레이어 팝업 배경 -->
<div id="layerPopupBg" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:99999999;"></div>


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
                        <li class="list-group-item d-flex align-items-center"
                            style="padding-left:2.5rem;margin-bottom:0.2em">
                            <input class="form-check-input me-2" type="radio" name="s_form_idx"
                                id="s_form_idx_<?php echo _elm($form, 'F_IDX'); ?>"
                                value="<?php echo _elm($form, 'F_IDX'); ?>">
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
<input type="hidden" name="s_grade" value="">
<input type="hidden" name="s_start_date" value="">
<input type="hidden" name="s_end_date" value="">
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
let show_mb_idx = '';
</script>

<?php
$owensView->setFooterJs('/assets/js/membership/lists.js');
$owensView->setFooterJs('/assets/js/membership/detail.js');
$owensView->setFooterJs('/assets/js/membership/register.js');

$script = "

";

$owensView->setFooterScript($script);