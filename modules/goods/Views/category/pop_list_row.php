<?php
    helper(['owens','owens_form', 'owens_url']);
    $view_datas = $owensView->getViewDatas();
    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

    if (isset($pageDatas) === false) {
        $pageDatas = [];
    }
    $sQueryString = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aConfig = _elm($view_datas, 'aConfig', []);

    $aLists = _elm($view_datas, 'aDatas', []);
    $aCateTreeLists = _elm($view_datas, 'cate_tree_lists', []);

?>
    <div class="modal-header">
        <h5 class="modal-title" id="iconsModalLabel">관리</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body" style="flex: 1 1 auto;overflow-y: auto;">
        <div>
            <div class="row row-deck row-cards">
                <!-- 카테고리 카드 -->
                <div class="col-md-6" style="height: 730px">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card" style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9; ">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4" fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-2">
                                        카테고리
                                    </p>

                                </div>
                                <!-- 추가 버튼 -->
                                <div>
                                    <?php
                                    echo getIconButton([
                                        'txt' => '1차 카테고리 생성',
                                        'icon' => 'add',
                                        'buttonClass' => 'btn-sm btn-white',
                                        'buttonStyle' => '',
                                        'width' => '21',
                                        'height' => '20',
                                        'stroke' => '#1D273B',
                                        'extra' => [
                                            'type' => 'button',
                                            'onclick' => 'openLayer(\'\',0);',
                                        ]
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" style="overflow-y: auto">
                            <!-- 카테고리 wrapper -->
                            <div id="category-list">
                                <?php
                                if (empty($aCateTreeLists) === false) {
                                    foreach ($aCateTreeLists as $kIDX => $vCATE) {
                                        $cate_idx = _elm($vCATE, 'C_IDX', 0);
                                        $parent_idx = _elm($vCATE, 'C_PARENT_IDX', 0);
                                ?>
                                <div class="category-wrapper" data-idx="<?php echo $cate_idx ?>">
                                    <div class="category-div d-flex align-items-center justify-content-between detail" data-idx="<?php echo $cate_idx ?>" data-parent-idx="<?php echo $parent_idx ?>">
                                        <div class="d-flex align-items-center gap-2">
                                            <!-- 토글 버튼 -->
                                            <svg class="toggle-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="openLayer('','<?php echo $cate_idx?>')">
                                                <rect width="16" height="16" rx="4" fill="#616876"></rect>
                                                <path d="M4.5 8H11.5" stroke="white" stroke-width="1.66666" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 11.5L8 4.5" stroke="white" stroke-width="1.66666" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>


                                            <!-- 카테고리명 -->
                                            <p>[<?php echo _elm($vCATE, 'C_CATE_CODE') ?>] <?php echo _elm($vCATE, 'C_CATE_NAME') ?> ( <?php echo count( _elm($vCATE, 'CHILD', []) ) ?> )</p>
                                        </div>
                                        <!-- 위,아래 아이콘 -->
                                        <div class="move-icons">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32" fill="none">
                                            <g clip-path="url(#clip0_492_18580)">
                                                <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"/>
                                                <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"/>
                                                <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"/>
                                                <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"/>
                                                <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"/>
                                                <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"/>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_492_18580">
                                                <rect width="32" height="32" fill="white"/>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        </div>
                                    </div>
                                    <?php
                                    if (empty(_elm($vCATE, 'CHILD')) === false) {
                                        foreach (_elm($vCATE, 'CHILD', []) as $kIDX_CHILD => $vCATE_CHILD) {
                                            $cate_idx = _elm($vCATE_CHILD, 'C_IDX', 0);
                                            $parent_idx = _elm($vCATE_CHILD, 'C_PARENT_IDX', 0);
                                    ?>
                                    <div class="child-container" data-idx="<?php echo $cate_idx ?>" data-parent-idx="<?php echo $parent_idx ?>">
                                        <!-- 2차 카테고리 -->
                                        <div class="child-category-wrapper detail" data-idx="<?php echo $cate_idx ?>" data-parent-idx="<?php echo $parent_idx ?>">
                                            <div class="child-category d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-2">
                                                    <!-- 토글 버튼 -->
                                                    <svg class="toggle-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="openLayer('','<?php echo $cate_idx?>')" >
                                                        <rect width="16" height="16" rx="4" fill="#616876"></rect>
                                                        <path d="M4.5 8H11.5" stroke="white" stroke-width="1.66666" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M8 11.5L8 4.5" stroke="white" stroke-width="1.66666" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                    <!-- 카테고리명 -->
                                                    <p>[<?php echo _elm($vCATE_CHILD, 'C_CATE_CODE') ?>] <?php echo _elm($vCATE_CHILD, 'C_CATE_NAME') ?> ( <?php echo count( _elm($vCATE_CHILD, 'CHILD', []) ) ?> ) </p>
                                                </div>
                                                <div class="move-icons">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32" fill="none">
                                                    <g clip-path="url(#clip0_492_18580)">
                                                        <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"/>
                                                        <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"/>
                                                        <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"/>
                                                        <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"/>
                                                        <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"/>
                                                        <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_492_18580">
                                                        <rect width="32" height="32" fill="white"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                                </div>
                                            </div>
                                            <?php
                                            if (empty($vCATE_CHILD['CHILD']) === false) {
                                                foreach (_elm($vCATE_CHILD, 'CHILD', []) as $kIDX_GRAND_CHILD => $vCATE_GRAND_CHILD) {
                                                    $cate_idx = _elm($vCATE_GRAND_CHILD, 'C_IDX', 0);
                                                    $parent_idx = _elm($vCATE_GRAND_CHILD, 'C_PARENT_IDX', 0);
                                            ?>
                                            <div class="child-container2" data-idx="<?php echo $cate_idx ?>" data-parent-idx="<?php echo $parent_idx ?>">
                                                <!-- 3차 카테고리 -->
                                                <div class="child-category d-flex align-items-center justify-content-between detail" data-idx="<?php echo $cate_idx ?>" data-parent-idx="<?php echo $parent_idx ?>">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <!-- 토글 버튼 -->

                                                        <!-- 카테고리명 -->
                                                        <p>[<?php echo _elm($vCATE_GRAND_CHILD, 'C_CATE_CODE') ?>] <?php echo _elm($vCATE_GRAND_CHILD, 'C_CATE_NAME') ?></p>
                                                    </div>
                                                    <div class="move-icons">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 32 32" fill="none">
                                                        <g clip-path="url(#clip0_492_18580)">
                                                            <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"/>
                                                            <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"/>
                                                            <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"/>
                                                            <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"/>
                                                            <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"/>
                                                            <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"/>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_492_18580">
                                                            <rect width="32" height="32" fill="white"/>
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 카테고리 정보 카드 -->
                <div id="viewData" class="col-md" style="height: fit-content"></div>
            </div>
        </div>

    </div>
</div><!-- Modal E-->
<script>
function openLayer( cateIdx, parentIdx ){
    let data = 'parentIdx='+parentIdx;
    let url  = '/apis/goods/cateRegister';

    if( cateIdx != '' ){
        data = 'cate_idx='+cateIdx+'&parentIdx='+parentIdx;
        url  = '/apis/goods/cateDetail';
    }
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        processData: false,
        cache: false,
        beforeSend: function() {
            $('#preloader').show();
        },
        success: function(response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status == 'false') {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }
            $('#viewData').empty().html( response.page_datas.detail );
            bindEventListeners();
            //restoreAccordionState();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        },
        complete: function() { }
    });
    // 모달 열기
}
setTimeout( function(){openLayer('', 0);}, 200 );
function getSearchList( parentIdx = 0 ){

    $.ajax({
        url: '/apis/goods/getCategoryLists',
        method: 'POST',
        data: 'parentIdx='+parentIdx,
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

            $('#category-list').empty().html( response.page_datas.lists_row );
            initSortable();
            //restoreAccordionState();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            $('#preloader').hide();
            return false;
        },
        complete: function () { }
    });
}

function initSortable() {
    // 1차 카테고리 드래그 앤 드롭 설정
    $("#category-list").sortable({
        handle: ".move-icons",
        items: "> .category-wrapper",
        stop: function (event, ui) {
            var order = [];
            $("#category-list .category-wrapper").each(function () {
                order.push($(this).data("idx"));
            });

            updateCategoryOrder(order);
        }
    });

    // 2차 및 3차 카테고리 드래그 앤 드롭 설정
    $(".category-wrapper").each(function () {
        var parentIdx = $(this).data("idx");

        // 2차 카테고리
        $(this).find(".child-container").sortable({
            connectWith: ".category-wrapper[data-idx='" + parentIdx + "'] .child-container",
            handle: ".move-icons",
            items: "> .child-category-wrapper",
            stop: function (event, ui) {
                if (ui.sender) return; // 중복 업데이트 방지
                var order = [];
                $(".child-container[data-parent-idx='" + parentIdx + "'] .child-category-wrapper").each(function () {
                    order.push($(this).data("idx"));
                });

                updateCategoryOrder(order);
            }
        });

        // 3차 카테고리
        $(this).find(".child-category-wrapper").each(function () {
            var childParentIdx = $(this).data("idx");
            $(this).find(".child-container2").sortable({
                connectWith: ".child-container2",
                handle: ".move-icons",
                items: "> .child-category",
                stop: function (event, ui) {
                    if (ui.sender) return; // 중복 업데이트 방지
                    var order = [];
                    $(".child-container2[data-parent-idx='" + childParentIdx + "'] .child-category").each(function () {
                        order.push($(this).data("idx"));
                    });

                    updateCategoryOrder(order);

                }
            });
        });
    });
}

function bindEventListeners() {
    // 아코디언 기능 및 아이콘 토글
    $('.category-div, .child-category, .child-container2').off('dblclick').on('dblclick', function (event) {
        if ($(event.target).is('svg') || $(event.target).closest('svg').length) {
            return; // SVG를 클릭한 경우 아코디언 기능을 작동하지 않음
        }
        var $childContainers = $(this).nextAll('.child-container, .child-container2');
        if ($childContainers.length) {
            $childContainers.toggle(200);
            //saveAccordionState();
        }
    });

    // 상세 정보를 여는 클릭 이벤트
    $('.detail').off('click').on('click', function (event) {
        event.stopPropagation(); // 이벤트 버블링 방지
        $('.selected-add-category').removeClass('selected-add-category');
        $('.selected-category').removeClass('selected-category');
        if (!$(event.target).closest('svg').length) {
            if ( $(this).hasClass('category-div') ) {
                $(this).addClass('selected-category');
            } else {
                $(this).find('.child-category').addClass('selected-category');
            }
            openLayer($(this).data('idx'), $(this).attr('data-parent-idx'));
        }else{
            if( !$(event.target).closest('svg').hasClass('move-up') ){
                if ( $(this).hasClass('category-div') ) {
                    $(this).addClass('selected-add-category');
                } else {
                    $(this).find('.child-category').addClass('selected-add-category');
                }
            }

        }
    });
}

$(document).ready(function () {
    setTimeout(function(){
        initSortable();
        bindEventListeners();
    }, 1000);
    // restoreAccordionState();
});


function updateCategoryOrder(order) {
    $.ajax({
        url: '/apis/goods/updateCategoryOrder',
        type: 'POST',
        data: { order: order },
        beforeSend:function(){
            $('#preloader').hide();
        },
        success: function(response) {
            console.log('Order updated:', response);
            $('#preloader').hide();
            getCategoryDropDown();
        },
        error: function(xhr, status, error) {
            $('#preloader').hide();
            console.error('Error updating order:', status, error);
        }
    });
}


function deleteCategoryConfirm( cate_idx )
{
    box_confirm('카테고리를 삭제하시겠습니까?\n삭제 시 하위 카테고리 전부가 삭제됩니다.', 'q', '', deleteCategory, cate_idx);
}
function deleteCategory( cate_idx ){
    $.ajax({
        url: '/apis/goods/deleteCategory',
        type: 'post',
        data: 'cate_idx='+cate_idx,
        dataType: 'json',
        cache: false,
        beforeSend: function() {
            $('#preloader').show();
        },
        success: function(response) {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status == 'false')
            {
                var error_message = '';
                error_message = error_lists.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'i');
                }

                return false;
            }
            //getSearchList();
            getSearchList();
            openLayer('',0);
            getCategoryDropDown();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        },
        complete: function() { }
    });
}


$(document).on( 'keyup', '#frm_pop_modify #i_cate_name', function(e){
    e.preventDefault();
    if( e.keyCode == 13 ){
        frmModifyConfirm(e);
    }
} );

function frmModifyConfirm( event ){
    event.preventDefault();
    box_confirm('수정 하시겠습니까?', 'q', '', frmModify);
}
function frmModify(){
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_pop_modify').find('input, button, select');
    var isSubmit = true;

    if ($.trim($('#i_cate_name').val()) == '') {
        _form_error('i_name', '커태고리명을 입력하세요.');
        isSubmit = false;
    }

    if (isSubmit == false) {
        var error_message = error_lists.join('<br />');
        box_alert(error_message, 'e');

        inputs.prop('disabled', false);
        return false;
    }

    // 폼 전송 로직 추가
    var formData = new FormData($('#frm_pop_modify')[0]);
    $.ajax({
        url: '/apis/goods/categoryDetailProc',
        method: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {
            inputs.prop('disabled', true);
            setTimeout(function () { inputs.prop('disabled', false); }, 3000);
            $('#preloader').show();
        },
        complete: function() { },
        success: function(response)
        {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status != 200)
            {
                var error_message = '';
                error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            getSearchList();
            getCategoryDropDown();
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        }
    });
}

$(document).on( 'keyup', '#frm_pop_register #i_cate_name', function(e){
    if( e.keyCode == 13 ){
        frmRegiserConfirm(e);
    }
} );

function frmRegiserConfirm( event ){
    event.preventDefault();
    box_confirm('등록 하시겠습니까?', 'q', '', frmRegiser);
}
function frmRegiser(){
    error_lists = [];
    $('.error_txt').html('');

    var inputs = $('#frm_pop_register').find('input, button, select');
    var isSubmit = true;

    if ($.trim($('#frm_pop_register #i_cate_name').val()) == '') {
        _form_error('i_cate_name', '커태고리명을 입력하세요.');
        isSubmit = false;
    }

    if (isSubmit == false) {
        var error_message = error_lists.join('<br />');
        box_alert(error_message, 'e');

        inputs.prop('disabled', false);
        return false;
    }

    // 폼 전송 로직 추가
    var formData = new FormData($('#frm_pop_register')[0]);
    $.ajax({
        url: '/apis/goods/categoryRegisterProc',
        method: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {
            inputs.prop('disabled', true);
            $('#preloader').show();
        },
        complete: function() { },
        success: function(response)
        {
            submitSuccess(response);
            $('#preloader').hide();
            if (response.status != 200)
            {
                var error_message = '';
                error_message = response.errors.join('<br />');
                if (error_message != '') {
                    box_alert(error_message, 'e');
                }
                return false;
            }

            getSearchList();
            openLayer('',0);
            getCategoryDropDown()
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            submitError(jqXHR.status, errorThrown);
            console.log(textStatus);
            $('#preloader').hide();
            return false;
        }
    });
}
</script>

<?php


$script = "
";
$owensView->setFooterScript($script);

