<?php
    helper(['owens','owens_form', 'owens_url', 'form']);
    $view_datas = $owensView->getViewDatas();
    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

    if (isset($pageDatas) === false) {
        $pageDatas = [];
    }
    $sQueryString = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aConfig = _elm($view_datas, 'aConfig', []);
    $aLists = _elm($view_datas, 'lists', []);
    $aTargetId = _elm($view_datas, 'aTargetId', '');
    $pagiation = _elm( $pageDatas, 'pagination' );
    $couponInfo = _elm( $view_datas, 'couponInfo', [] );


?>
<style>
.searchResultLayer {
    position: absolute;
    background-color: #fff;
    border: 1px solid #ddd;
    width: 120px;
    max-height: 200px;
    overflow-y: auto;
    display: none;
}
.search-result-item:hover {
    background-color: #f1f1f1;
}
.search-result-item.active {
    background-color: #f0f0f0; /* 선택된 항목의 배경색 */
}
</style>
    <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
        <div>
            <div class="row row-deck row-cards">

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
                                검색
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
                    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_sub_search', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                    <input type="hidden" name="coupon_idx" value="<?php echo $aTargetId?>">
                    <input type="hidden" name="page" id="page" value="1">
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <tbody>
                                <colgroup>
                                    <col style="width:10%;">
                                    <col style="width:70%;">
                                </colgroup>
                                <tr>
                                    <th class="no-border-bottom">검색</th>
                                    <td colspan='3' class="no-border-bottom">
                                        <div class="form-inline">
                                            <?php
                                                $options  = ['MB_NM'=>'회원명', 'MB_USERID'=>'회원아이디'];
                                                $extras   = ['id' => 's_condition', 'class' => 'form-select', 'style' => 'max-width: 150px;', ];
                                                $selected = '';
                                                echo getSelectBox('s_condition', $options, $selected, $extras);
                                            ?>
                                            <input type="text" class="form-control" style="width:15.2rem" name="s_keyword" id="s_keyword">
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <th class="no-border-bottom">상태</th>
                                    <td colspan='3' class="no-border-bottom" >
                                        <?php
                                            $options  = ['' => '전체', 'Y'=>'사용', 'N'=>'미사용'];
                                            $extras   = ['id' => 's_status', 'class' => 'form-select', 'style' => 'max-width: 150px;', ];
                                            $selected = '';
                                            echo getSelectBox('s_status', $options, $selected, $extras);
                                        ?>

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
                            'buttonStyle' => 'width: 120px; height: 34px;background-color:#206BC4',
                            'width' => '21',
                            'height' => '20',
                            'stroke' => 'white',
                            'extra' => [
                                'onclick' => 'getSubSearchList();',
                            ]
                        ]);
                    ?>
                    <?php
                        if( _elm( $couponInfo, 'C_PUB_GBN' ) != 'A' && _elm( $couponInfo, 'C_STATUS' ) == 'Y'){
                            echo getIconButton([
                                'txt' => '쿠폰생성',
                                'icon' => 'add',
                                'buttonClass' => 'btn btn-secondary',
                                'buttonStyle' => 'width: 120px; height: 34px;',
                                'width' => '21',
                                'height' => '20',
                                'stroke' => 'white',
                                'extra' => [
                                    'onclick' => 'openMakeInssueConfirm();',
                                ]
                            ]);
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div>
        <div class="row row-deck row-cards">

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

            <div class="card-body">
                <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_sub_lists',  'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                    <input type="hidden" name="page">
                    <div class="table-responsive ">
                        <table class="table table-vcenter small" id="subList">
                            <colgroup>
                                <!-- <col style="width:1%"> -->
                                <col style="width:1%">
                                <col style="width:*%">
                                <col style="width:20%">
                                <col style="width:10%">
                                <col style="width:10%">
                                <col style="width:5%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <!-- <th>
                                        <div class="checkbox checkbox-single">
                                            <?php
                                            $setParam = [
                                                'name' => '',
                                                'id' => 'checkAllPop',
                                                'value' => '',
                                                'label' => '',
                                                'checked' => false,
                                                'extraAttributes' => [
                                                    'class'=>'checkAll',
                                                    'aria-label' => 'Single checkbox One',
                                                    'style' => "padding:0"
                                                ]
                                            ];
                                            echo getCheckBox( $setParam );
                                            ?>
                                        </div>
                                    </th> -->
                                    <th>번호</th>
                                    <th>쿠폰코드</th>
                                    <th>사용자</th>
                                    <th>사용일</th>
                                    <th>사용주문</th>
                                    <th>삭제</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <!--페이징-->
                    <div class="pagination-wrapper" id="pagination">
                        <?php echo $pagiation?>
                    </div>
                </div>
                <?php echo form_close()?>
            </div>
        </div>

    </div>
</div>
<!-- Modal S-->
<div class="modal fade" id="cntModal" tabindex="-1" style="margin-top:3em; z-index:1000000000000000000;" aria-labelledby="cntModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 250px; width: 80%; margin-top: 250px;background-color:#fff;">
        <div class="modal-content" style="max-width: 250px; width: 80%;max-height: 90vh; display: flex; flex-direction: column; width: 80vh;border:1px solid #ccc">
            <div class="modal-header">
                <h5 class="modal-title" id="cntModalLabel">쿠폰 발행수량</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="flex: 1 1 auto; overflow-y: auto;">
                <div class="viewData">
                    <div>
                        <input type="text" name="issueCnt" class="form-control" value="" style="text-align:right" placeholder="발급수량 입력" maxlength="3">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary" onclick="makeInssue()">입력</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal E-->
<script>
$(document).ready(function() {
    const checkAll = document.querySelector('#checkAllPop');
    const checkItems = document.querySelectorAll('.check-item-pop');

    if (checkAll && checkItems.length > 0) {
        checkAll.addEventListener('change', function () {
            const isChecked = this.checked;
            checkItems.forEach(function (checkbox) {
                checkbox.checked = isChecked;
            });
        });

        // 개별 체크박스 체크/체크 해제 시 전체 체크박스 상태 업데이트
        checkItems.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const allChecked = document.querySelectorAll('.check-item-pop:checked').length === checkItems.length;
                checkAll.checked = allChecked;
            });
        });
    }
});
function openMakeInssueConfirm(){
    $('[name=issueCnt]').val('');
    //var modal = new bootstrap.Modal(document.getElementById(id));
    var modalElement = document.getElementById('cntModal');
    var modal = new bootstrap.Modal(modalElement, {
        backdrop: 'static', // 마스크 클릭해도 닫히지 않게 설정
        keyboard: true     // esc 키로 닫히지 않게 설정
    });

    modal.show();
}
function makeInssue()
{
    let cpn_idx = $('#frm_sub_search [name=coupon_idx]').val();
    let issue_cnt = $("#cntModal [name=issueCnt]").val();
    if( issue_cnt == 0 || issue_cnt == '' ){
        box_alert( '수량을 입력해주세요.', 'i' );
        return false;
    }
    let frm = $('#frm_sub_search');
    var inputs = frm.find('input, button, select');
    $.ajax({
        url: '/apis/promotion/makeCpnIssue',
        method: 'POST',
        data: 'cpn_idx='+cpn_idx+'&issue_cnt='+issue_cnt,
        dataType: 'json',
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
            inputs.prop('disabled', true);
        },
        success: function (response) {
            submitSuccess(response);
            $('#preloader').hide();
            inputs.prop('disabled', false);
            if (response.status == 'false') {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $('#cntModal .btn-close').trigger('click');

            getSubSearchList();
            const parentFrm = $("#frm_search");
            getSearchList( parentFrm.find( '[name=page]' ).val() );
            //restoreAccordionState();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            inputs.prop('disabled', false);
            return false;
        },
        complete: function () { }
    });
}
let isHovered = false; // 검색 결과 레이어에 hover 상태를 저장할 변수

// 검색 결과 레이어에 마우스가 올라갔을 때
$(document).on('mouseenter', '.searchResultLayer', function() {
    isHovered = true; // 마우스가 레이어 위에 있을 때 true로 설정
});

// 검색 결과 레이어에서 마우스가 벗어났을 때
$(document).on('mouseleave', '.searchResultLayer', function() {
    isHovered = false; // 마우스가 레이어에서 벗어나면 false로 설정
});

//사용자 직접 등록
$(document).on('dblclick', '.userInput', function() {
    let $this = $(this);
    if( $this.find('.usedUserInfo').text().trim() != '' ){
        box_alert('사용자가 등록되어 있습니다. 다른쿠폰을 선택해주세요.','e');
        return false;
    }
    $this.find('.insertUserForm').html(`
        <input type="text" class="form-control userSearchInput" style="width: 96px;height:24px" name="userSearchFrm" placeholder="사용자 검색">
        <div class="searchResultLayer" style="position: absolute; background-color: #fff; border: 1px solid #ddd; width: 120px; max-height: 200px; overflow-y: auto; display: none;"></div>
    `).show();
});

// 검색 인풋 필드에서 포커스 아웃 되었을 때
$(document).on('focusout', '.userSearchInput', function() {
    let $this = $(this);
    setTimeout(function() {
        // 검색 인풋과 결과 레이어 모두 포커스가 없고, hover 상태도 아닐 때만 숨기기
        if (!$this.is(':focus') && !isHovered) {
            $this.closest('.insertUserForm').html('').hide();
        }
    }, 100);
});


$(document).on('mousedown', '.search-result-item', function() {
    let $this = $(this); // 클릭된 요소를 가져옴
    let selectedUser = $this.text(); // 클릭된 항목의 텍스트
    let selectedUserIdx = $this.data('idx'); // 클릭된 항목의 사용자 idx
    let issueIdx = $this.closest('td').attr('data-issue-idx'); // data-issue-idx 속성

    cpnJoinUserConfirm( $this, issueIdx, selectedUserIdx, selectedUser );
});


$(document).on('keyup', '.userSearchInput', function(e) {
    e.stopPropagation(); // 이벤트 전파를 막음

    let $this = $(this);
    let query = $this.val().trim();
    let $resultLayer = $this.next('.searchResultLayer');
    let $resultItems = $resultLayer.find('.search-result-item');
    let $activeItem = $resultItems.filter('.active'); // 현재 활성화된 항목 찾기

    // 방향키로 항목을 선택할 수 있게 처리
    if (e.key === 'ArrowDown') {
        e.preventDefault(); // 기본 스크롤 방지
        if ($activeItem.length === 0) {
            // 첫 번째 항목 선택
            $resultItems.first().addClass('active');
        } else {
            // 다음 항목으로 이동
            let $nextItem = $activeItem.next('.search-result-item');
            if ($nextItem.length > 0) {
                $activeItem.removeClass('active');
                $nextItem.addClass('active');
            }
        }
        return;
    }

    if (e.key === 'ArrowUp') {
        e.preventDefault(); // 기본 스크롤 방지
        if ($activeItem.length > 0) {
            // 이전 항목으로 이동
            let $prevItem = $activeItem.prev('.search-result-item');
            if ($prevItem.length > 0) {
                $activeItem.removeClass('active');
                $prevItem.addClass('active');
            }
        }
        return;
    }

    if (e.key === 'Enter') {
        e.preventDefault(); // 기본 submit 방지
        if ($activeItem.length > 0) {
            // 선택된 항목 클릭 시와 동일한 처리
            let selectedUser = $activeItem.text();
            let selectedUserIdx = $activeItem.data('idx');
            let issueIdx        = $this.closest('td').attr('data-issue-idx') ;

            cpnJoinUserConfirm( $this, issueIdx, selectedUserIdx, selectedUser );


        }
        return;
    }

    // 검색어가 있는지 확인
    if (query.length > 0) {
        // 서버로 검색 요청을 보내는 부분 (예시로 jQuery Ajax 사용)
        $.ajax({
            url: '/apis/membership/simpleSearch',  // 실제 API 경로로 변경 필요
            type: 'GET',
            data: { query: query },
            success: function(response) {
                // 검색 결과를 받아서 결과 레이어에 출력
                let resultHtml = '';
                if (response.data.length > 0) {
                    response.data.forEach(function(user) {
                        resultHtml += `<div class="search-result-item" style="padding: 5px; cursor: pointer;" data-idx="${user.MB_IDX}">${user.MB_NM} ( ${user.MB_USERID} )</div>`;
                    });
                } else {
                    resultHtml = '<div style="padding: 5px;">검색 결과가 없습니다.</div>';
                }

                $resultLayer.html(resultHtml).show();
            }
        });
    } else {
        $resultLayer.hide();
    }
});

function cpnJoinUserConfirm( _obj, _issueIdx,_mb_idx, _text ){
    let param = {
        obj: _obj,
        mb_idx: _mb_idx,
        issueIdx: _issueIdx,
        text: _text,
    }
    box_confirm( _text+' 사용자에게 쿠폰을 지급하시겠습니까?', 'q', '', cpnJoinUser, param);
}
function cpnJoinUser( param ){

    let data = 'mbIdx='+param.mb_idx+'&issueIdx='+param.issueIdx;
    $.ajax({
        url: '/apis/promotion/couponJoinUser',
        method: 'POST',
        data: data,
        dataType: 'json',
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        success: function (response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status == 'false') {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }



            const frm = $("#frm_sub_search");
            getSubSearchList( frm.find( '[name=page]' ).val() );

        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            return false;
        },
        complete: function () { }
    });

}

function deleteIssueConfirm( issueIdx )
{
    if( issueIdx == '' ){
        box_alert( '잘못된 접근입니다. 다시 시도해주세요.', 'e' );
        return false;
    }
    if( issueIdx < 1 ){
        box_alert( '잘못된 접근입니다. 다시 시도해주세요.', 'e' );
        return false;
    }

    box_confirm('발급된 쿠폰을 삭제 하시겠습니까?', 'q', '', deleteIssue, issueIdx);

}

function deleteIssue( issueIdx ){
    let data = 'issueIdx='+issueIdx;
    $.ajax({
        url: '/apis/promotion/deleteIssueData',
        method: 'POST',
        data: data,
        dataType: 'json',
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        success: function (response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status == 'false') {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            const frm = $("#frm_sub_search");
            getSubSearchList( frm.find( '[name=page]' ).val() );
            const parentFrm = $("#frm_search");
            getSearchList( parentFrm.find( '[name=page]' ).val() );

        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            return false;
        },
        complete: function () { }
    });
}

function getSubSearchList( $page  ){

    const frm = $("#frm_sub_search");
    frm.find( '[name=page]' ).val( $page );
    var inputs = frm.find('input, button, select');

    $.ajax({
        url: '/apis/promotion/couponIssueLists',
        method: 'POST',
        data: frm.serialize(),
        dataType: 'json',
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
            inputs.prop('disabled', true);
        },
        success: function (response) {
            submitSuccess(response);
            $('#preloader').hide();
            inputs.prop('disabled', false);
            if (response.status == 'false') {
                var error_message = response.error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            $('#frm_sub_lists tbody').empty().html( response.page_datas.lists_row );
            $("#frm_sub_lists #pagination").empty().html( response.page_datas.pagination );

            //restoreAccordionState();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            inputs.prop('disabled', false);
            return false;
        },
        complete: function () { }
    });
}


$(document).on('keyup', '#frm_sub_search [name=s_keyword]', function(e){
    e.preventDefault();
    if( e.keyCode == 13 ){
        getSubSearchList();
    }
});

subPagination.initPagingNumFunc(getSubSearchList);
subPagination.initPagingSelectFunc(getSubSearchList);


</script>

<?php


$script = "
";
$owensView->setFooterScript($script);

