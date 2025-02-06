<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }
    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aConfig          = _elm($pageDatas, 'aConfig', []);

    $aGetData         = _elm( $pageDatas, 'getData', [] );


    $aOrderTopInfo    = _elm( $pageDatas, 'orderTopInfo' );
    $aUserInfo        = _elm( $pageDatas, 'userInfo' );

    $aDatas           = _elm( $pageDatas, 'aDatas', [] );

?>
<link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<link href="/plugins/select2/select2.css" rel="stylesheet" />
<script src="/plugins/select2/select2.js"></script>


<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_modify', 'autocomplete' => 'off', 'enctype'=>"multipart/form-data" ]); ?>
<input type="hidden" name="i_goods_idx" value="<?php echo _elm( $aDatas, 'G_IDX' )?>">
<input type="hidden" name="i_description">
<input type="hidden" name="i_content_pc">
<input type="hidden" name="i_content_mobile">
<input type="hidden" name="i_relation_goods_idxs">
<input type="hidden" name="i_add_goods_idxs">



<!-- 본문 -->
<div class="container-fluid">
    <!-- 카트 타이틀 -->
    <div class="card-title" style="display: flex; justify-content: space-between; align-items: center;">
        <!-- 왼쪽: 제목 -->
        <h3 class="h3-c" style="margin: 0;"><?php echo _elm( $aOrderTopInfo, 'odrId' );?></h3>

        <!-- 오른쪽: 아이콘들 -->
        <div style="display: flex; gap: 10px;">
            <!-- 아이콘 예시 -->
            <span class="icon-example" style="width: 24px; height: 24px; background-color: #ccc; border-radius: 50%; display: inline-block;"></span>
            <span class="icon-example" style="width: 24px; height: 24px; background-color: #ccc; border-radius: 50%; display: inline-block;"></span>
            <span class="icon-example" style="width: 24px; height: 24px; background-color: #ccc; border-radius: 50%; display: inline-block;"></span>
        </div>
    </div>

    <!-- 기타 정보는 아래로 -->
    <div style="margin-top: 10px; clear: both; display: flex; justify-content: space-between; align-items: center;">
        <div style="float: left;">
            <?php echo _elm( $aUserInfo, 'MB_NM', '외부채널 구매', true ); ?>
            <span> <?php echo _elm( $aOrderTopInfo, 'odrInitAt' ); ?> </span>
        </div>
        <div id="deviceIcon" style="float: right;">
            <!-- 기기 아이콘 -->
        </div>
    </div>

    <div class="d-flex gap-3">
        <!-- 좌측 영역 -->
        <div class="col-md-10">
            <div class="row row-deck row-cards" style="">
                <!-- 기본설정 카드 -->
                <div class="col-md">
                    <div class="card" style="height:fit-content">
                        <div class='tab-wrap' style="line-height:32px;">
                            <ul class="nav nav-tabs" id="orderStatusTabs" role="tablist" style="position: relative;">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="order-tab" data-bs-toggle="tab" href="#order" role="tab" aria-controls="order" aria-selected="true">
                                        주문내역 <span id="orderCnt"></span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="delivery-tab" data-bs-toggle="tab" href="#delivery" role="tab" aria-controls="delivery" aria-selected="false">
                                        배송<span id="deliveryCnt"></span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="cancel-tab" data-bs-toggle="tab" href="#cancel" role="tab" aria-controls="cancel" aria-selected="false">
                                        취소내역<span id="cancelCnt"></span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="exchange-tab" data-bs-toggle="tab" href="#exchange" role="tab" aria-controls="exchange" aria-selected="false">
                                        교환내역<span id="exchangeCnt"></span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="return-tab" data-bs-toggle="tab" href="#return" role="tab" aria-controls="return" aria-selected="false">
                                        반품내역<span id="returnCnt"></span>
                                    </a>
                                </li>
                                <!-- 오른쪽 끝으로 이동한 div -->
                                <div style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                                    <button type="button" style="background: none; border: none; padding: 0; cursor: pointer;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11 12C11 12.2652 11.1054 12.5196 11.2929 12.7071C11.4804 12.8946 11.7348 13 12 13C12.2652 13 12.5196 12.8946 12.7071 12.7071C12.8946 12.5196 13 12.2652 13 12C13 11.7348 12.8946 11.4804 12.7071 11.2929C12.5196 11.1054 12.2652 11 12 11C11.7348 11 11.4804 11.1054 11.2929 11.2929C11.1054 11.4804 11 11.7348 11 12Z" stroke="#616876" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11 19C11 19.2652 11.1054 19.5196 11.2929 19.7071C11.4804 19.8946 11.7348 20 12 20C12.2652 20 12.5196 19.8946 12.7071 19.7071C12.8946 19.5196 13 19.2652 13 19C13 18.7348 12.8946 18.4804 12.7071 18.2929C12.5196 18.1054 12.2652 18 12 18C11.7348 18 11.4804 18.1054 11.2929 18.2929C11.1054 18.4804 11 18.7348 11 19Z" stroke="#616876" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M11 5C11 5.26522 11.1054 5.51957 11.2929 5.70711C11.4804 5.89464 11.7348 6 12 6C12.2652 6 12.5196 5.89464 12.7071 5.70711C12.8946 5.51957 13 5.26522 13 5C13 4.73478 12.8946 4.48043 12.7071 4.29289C12.5196 4.10536 12.2652 4 12 4C11.7348 4 11.4804 4.10536 11.2929 4.29289C11.1054 4.48043 11 4.73478 11 5Z" stroke="#616876" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </ul>
                        </div>
                        <div class="tab-content" id="myTabContent">
                            <!-- 입금대기 탭 내용 -->
                            <div class="tab-pane fade show active" id="order" role="tabpanel" aria-labelledby="order-tab">
                                <div class="card-body">
                                    <div class="input-group required">
                                        <label class="label body2-c">
                                            상품상세설명
                                            <span>*</span>
                                        </label>
                                    </div>
                                    <div class="required" style="padding-bottom:1.2rem">
                                        <div style="text-align: right;padding-bottom:10px">
                                        <?php
                                            echo getIconButton([
                                                'txt' => '확대',
                                                'icon' => 'search',
                                                'buttonClass' => 'btn btn-white',
                                                'buttonStyle' => 'width:80px; height: 36px',
                                                'width' => '21',
                                                'height' => '20',
                                                'stroke' => 'black',
                                                'extra' => [
                                                    'type' => 'button',
                                                    'onclick' => 'resizeEditor(contents_editor)',
                                                ]
                                            ]);
                                        ?>

                                        </div>
                                        <div id="editor-container">
                                            <div id="contents_editor"></div>
                                        </div>
                                    </div>
                                    <div class="input-group required">
                                        <label class="label body2-c">
                                            모바일 상세설명
                                            <span>*</span>
                                        </label>
                                        <?php
                                            $checked = false;
                                            if( _elm( $aDatas, 'G_CONTETN_IS_SAME_FLAG' ) == 'Y' ){
                                                $checked = true;
                                            }
                                            $setParam = [
                                                'name' => 'i_mobile_content_same_chk',
                                                'id' => 'i_mobile_content_same_chk_Y',
                                                'value' => 'Y',
                                                'label' => 'PC상세설명과 동일',
                                                'checked' => $checked,
                                                'extraAttributes' => [
                                                    'onclick' => '$(\'#m_content\').hide()',
                                                    'data-required' => '모바일 설명이 PC와 동일한지 선택하세요.',

                                                ]
                                            ];
                                            echo getRadioButton($setParam);
                                        ?>
                                        <?php
                                            $checked = false;
                                            if( _elm( $aDatas, 'G_CONTETN_IS_SAME_FLAG' ) == 'N' ){
                                                $checked = true;
                                            }
                                            $setParam = [
                                                'name' => 'i_mobile_content_same_chk',
                                                'id' => 'i_mobile_content_same_chk_N',
                                                'value' => 'N',
                                                'label' => '모바일 상세설명',
                                                'checked' => $checked,
                                                'extraAttributes' => [
                                                    'onclick' => '$(\'#m_content\').show()',
                                                    'data-required' => '모바일 설명이 PC와 동일한지 선택하세요.',
                                                ]
                                            ];
                                            echo getRadioButton($setParam);
                                        ?>
                                    </div>
                                    <div class="required" id="m_content" style="padding-bottom:1.2rem;<?php echo  _elm( $aDatas, 'G_CONTETN_IS_SAME_FLAG' ) == 'Y' ? 'display:none;' : '';?>">
                                        <div style="text-align: right;padding-bottom:10px">
                                            <?php
                                                echo getIconButton([
                                                    'txt' => '확대',
                                                    'icon' => 'search',
                                                    'buttonClass' => 'btn btn-white',
                                                    'buttonStyle' => 'width:80px; height: 36px',
                                                    'width' => '21',
                                                    'height' => '20',
                                                    'stroke' => 'black',
                                                    'extra' => [
                                                        'type' => 'button',
                                                        'onclick' => 'resizeEditor(m_contents_editor)',
                                                    ]
                                                ]);
                                            ?>
                                        </div>
                                        <div id="editor-container">
                                            <div id="m_contents_editor"></div>
                                        </div>
                                    </div>
                                    <div class="input-group required">
                                        <label class="label body2-c">
                                            자체상품코드
                                        </label>
                                        <input type="text" class="form-control" name="i_goods_local_code" data-max-length="100"
                                            style="border-top-right-radius:0px; border-bottom-right-radius: 0px; width: 1%"  value="<?php echo _elm( $aDatas, 'G_LOCAL_PRID' )?>"/>
                                        <span class="wordCount input-group-text"
                                            style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                                            <?php echo mb_strlen( _elm( $aDatas, 'G_LOCAL_PRID' ) )?>/100
                                        </span>
                                    </div>
                                    <div class="input-group required">
                                        <label class="label body2-c">
                                            구매 적립포인트
                                        </label>
                                        <input type="text" class="form-control" name="i_goods_add_point" data-max-length="100" numberwithcomma
                                            style="max-width:150px" value="<?php echo _elm( $aDatas, 'G_ADD_POINT' )?>"/>

                                    </div>
                                    <div class="input-group">
                                        <label class="label body2-c">
                                            펄핏 사이즈측정
                                        </label>
                                        <?php
                                            $checked = false;
                                            if( _elm( $aDatas, 'G_IS_PERFIT_FLAG' ) == 'N' ){
                                                $checked = true;
                                            }
                                            $setParam = [
                                                'name' => 'i_perfit_use',
                                                'id' => 'i_perfit_use_N',
                                                'value' => 'N',
                                                'label' => '사용안함',
                                                'checked' => $checked,
                                                'extraAttributes' => [
                                                ]
                                            ];
                                            echo getRadioButton($setParam);
                                        ?>
                                        <?php
                                            $checked = false;
                                            if( _elm( $aDatas, 'G_IS_PERFIT_FLAG' ) == 'Y' ){
                                                $checked = true;
                                            }
                                            $setParam = [
                                                'name' => 'i_perfit_use',
                                                'id' => 'i_perfit_use_Y',
                                                'value' => 'Y',
                                                'label' => '사용함',
                                                'checked' => $checked,
                                                'extraAttributes' => [
                                                ]
                                            ];
                                            echo getRadioButton($setParam);
                                        ?>
                                    </div>
                                    <div class="input-group">
                                        <label class="label body2-c">
                                            검색키워드
                                        </label>
                                        <input type="text" class="form-control" name="i_search_keyword"
                                            style="border-top-right-radius:0px; border-bottom-right-radius: 0px; width: 1%"  value="<?php echo _elm( $aDatas, 'G_SEARCH_KEYWORD' )?>" placeholder="바지,바지바지,여자바지,반바지"/>
                                    </div>
                                    <div class="input-group required">
                                        <label class="label body2-c">
                                            상품정보제공고시
                                        </label>
                                        <?php
                                            echo getIconButton([
                                                'txt' => '항목 불러오기',
                                                'icon' => 'add',
                                                'buttonClass' => 'btn',
                                                'buttonStyle' => 'width:130px; height: 36px',
                                                'width' => '21',
                                                'height' => '20',
                                                'stroke' => 'black',
                                                'extra' => [
                                                    'type' => 'button',
                                                    'onclick' => 'loadAddInfo();',
                                                ]
                                            ]);
                                        ?>
                                        <?php
                                            echo getIconButton([
                                                'txt' => '항목추가',
                                                'icon' => 'box_plus',
                                                'buttonClass' => 'btn',
                                                'buttonStyle' => 'width:130px; height: 36px',
                                                'width' => '21',
                                                'height' => '20',
                                                'stroke' => 'black',
                                                'extra' => [
                                                    'type' => 'button',
                                                    'onclick' => 'addRows( \'reqInfo\' );',
                                                ]
                                            ]);
                                        ?>
                                    </div>

                                    <div id="add_info_wrap" style="width:100%;padding:2.5rem;margin-bottom:1.3rem;border-radius: 4px;border: 1px solid var(----tblr-border-color, #E6E7E9);background: var(-----tblr-light, #F8FAFC);">
                                        <div class="table-responsive">
                                            <table class="table table-vcenter" id="aListsTable">
                                                <colgroup>
                                                    <col style="width:30%;">
                                                    <col style="*">
                                                    <col style="width:5%;">
                                                </colgroup>
                                                <thead>
                                                    <tr>
                                                        <th>항목</th>
                                                        <th>내용</th>
                                                        <th>삭제</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if( !empty( _elm( $aDatas, 'REQ_INFO' ) ) ){
                                                    foreach( _elm( $aDatas, 'REQ_INFO' ) as $rKey => $reqInfo ){
                                                ?>
                                                    <tr>
                                                        <td><input type="text" class="form-control" name="i_req_info_keys[]" value="<?php echo _elm( $reqInfo, 'I_KEY' )?>"></td>
                                                        <td><input type="text" class="form-control" name="i_req_info_values[]" value="<?php echo _elm( $reqInfo, 'I_VALUE' )?>"></td>
                                                        <td>
                                                        <?php
                                                            echo getIconAnchor([
                                                                'txt' => '',
                                                                'icon' => 'delete',
                                                                'buttonClass' => '',
                                                                'buttonStyle' => '',
                                                                'width' => '24',
                                                                'height' => '24',
                                                                'stroke' => '#616876',
                                                                'extra' => [
                                                                    'onclick' => 'deleteRows(this);',
                                                                ]
                                                            ]);
                                                        ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="input-group required">
                                        <label class="label body2-c">
                                            판매기간
                                        </label>
                                        <div class="input-icon">
                                            <input type="text" class="form-control  datetimepicker"
                                                name="i_sell_period_start_at" id="i_sell_period_start_at" value="<?php echo _elm( $aDatas, 'G_SELL_PERIOD_START_AT' )!= '0000-00-00 00:00:00' ? date('Y-m-d H:i:s', strtotime( _elm( $aDatas, 'G_SELL_PERIOD_START_AT' ) ) ):''?>" readonly>
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
                                        </div> <span style="margin: 0.4rem">~</span>
                                        <div class="input-icon">
                                            <input type="text" class="form-control  datetimepicker"
                                                name="i_sell_period_end_at" id="i_sell_period_end_at" value="<?php echo _elm( $aDatas, 'G_SELL_PERIOD_END_AT' )!= '0000-00-00 00:00:00' ? date('Y-m-d H:i:s', strtotime( _elm( $aDatas, 'G_SELL_PERIOD_END_AT' ) ) ): ''?>" readonly>
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

                                    <div class="input-group">
                                        <label class="label body2-c">
                                            판매단위
                                            <span>*</span>
                                        </label>
                                        <input type="text" class="form-control" name="i_sell_unit"
                                            style="border-top-right-radius:0px; border-bottom-right-radius: 0px; width: 1%" placeholder="EA" value="<?php echo _elm( $aDatas, 'G_SELL_UNIT' )?>"/>
                                    </div>
                                    <div class="input-group required">
                                        <label class="label body2-c"></label>
                                        예시) BOX/EA
                                    </div>
                                    <div class="input-group required">
                                        <label class="label body2-c">
                                            상품대표색상
                                        </label>
                                        <div class="color-options">
                                            <?php if( !empty( $aColorConfig ) ){
                                                foreach( $aColorConfig as $key => $colorData ){
                                            ?>
                                                <div class="color-option">
                                                    <div class="color-tootip">
                                                        <input type="radio" id="<?php echo _elm( $colorData, 'id' )?>" name="i_goods_color" value="<?php echo $key?>" <?php echo _elm( $aDatas, 'G_COLOR' ).';' == _elm( $colorData, 'color' ) ? 'checked' : ''?>>
                                                        <label for="<?php echo _elm( $colorData, 'id' )?>" style="background-color: <?php echo _elm( $colorData, 'color' )?>"class="label-custom" ></label>
                                                        <span class="tooltiptext" style=""><?php echo _elm( $colorData, 'text' )?></span>
                                                    </div>
                                                </div>
                                            <?php
                                                }
                                            }?>
                                        </div>
                                    </div>
                                    <div class="input-group required">
                                        <label class="label body2-c"></label>
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z" fill="#616876"/>
                                            <path d="M10 6.66669H10.0083" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M9.16663 10H9.99996V13.3333H10.8333" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        대표색상은 상품 검색시에 사용되며 색상 추가시 관리자에게 문의 주시면 됩니다.
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="delivery" role="tabpanel" aria-labelledby="delivery-tab">
                            delivery
                            </div>
                            <div class="tab-pane fade" id="cancel" role="tabpanel" aria-labelledby="cancel-tab">
                            cancel
                            </div>
                            <div class="tab-pane fade" id="exchange" role="tabpanel" aria-labelledby="exchange-tab">
                            exchange
                            </div>
                            <div class="tab-pane fade" id="return" role="tabpanel" aria-labelledby="return-tab">
                            return
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>


        <!-- 우측 영역 -->
        <div class="col-md-2">
            <!-- 분류 카드 S-->
            <div class="" style="height: fit-content">
                <!-- 묶음 시작 -->
                <div style="padding-bottom:1.1rem;margin-top:7px;">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        분류
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="input-group" style="position:relative">
                                <label class="label body2-c">
                                    카테고리
                                    <?php
                                    echo getIconAnchor([
                                        'txt' => '',
                                        'icon' => 'help',
                                        'width' => '16',
                                        'height' => '16',
                                        'stroke' => '#ccc',
                                        'extra' => [
                                            'type' => 'button',
                                            'onclick' => 'showTooltip(this,\'category\');',
                                        ]
                                    ]);
                                    ?>
                                </label>
                                <input type="text" class="form-control mt-2" id="ex-dropdown"
                                    placeholder="선택" readonly onclick="dropDownLayer('category')"/>
                                <div id="dropdown-layer-category" class="dropdown-layer" style="display:none">
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="select-item" id="select-category">
                                <?php
                                if( empty( _elm( $aDatas, 'GOODS_CATEGOTY_LISTS' ) ) === false ){
                                    foreach( _elm( $aDatas, 'GOODS_CATEGOTY_LISTS' ) as $cKey => $cLists ){
                                ?>
                                    <div class="dropdown-list-item" data-id="cate_<?php echo _elm( $cLists, 'C_IDX' )?>">
                                        <div class="dropdown-content">
                                            <div class="left-content">
                                                <?php
                                                    $checked = false;
                                                    if( _elm( $cLists, 'IS_MAIN' ) == 'Y' ){
                                                        $checked = true;
                                                    }
                                                    $setParam = [
                                                        'name' => 'i_is_category_main',
                                                        'id' => 'i_is_category_maincate_'._elm( $cLists, 'C_IDX' ),
                                                        'value' => _elm( $cLists, 'C_IDX' ),
                                                        'label' => '',
                                                        'checked' => $checked,
                                                        'extraAttributes' => [
                                                        ]
                                                    ];
                                                    echo getRadioButton($setParam);
                                                ?>
                                            </div>
                                            <div class="center-content">
                                                <div class="text"><?php echo _elm( $cLists, 'C_FULL_NAME' )?></div>
                                            </div>
                                            <div class="right-content">
                                                <svg class="tabler-icon-circle-x" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" fill="white"/>
                                                    <path d="M8 8L12 12L8 8ZM12 8L8 12L12 8Z" fill="white"/>
                                                    <path d="M8 8L12 12M12 8L8 12M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" stroke="#616876" stroke-linecap="round" stroke-linejoin="round"/>
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
                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    PC쇼핑몰 노출상태
                                </label>
                                <div class="form-inline">
                                    <?php
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_PC_OPEN_FLAG' ) == 'Y'){
                                            $checked = true;
                                        }

                                        $setParam = [
                                            'name' => 'i_is_pc_open',
                                            'id' => 'is_pc_open_Y',
                                            'value' => 'Y',
                                            'label' => '노출함',
                                            'checked' => $checked,
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                    <?php
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_PC_OPEN_FLAG' ) == 'N'){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_is_pc_open',
                                            'id' => 'is_pc_open_N',
                                            'value' => 'N',
                                            'label' => '노출안함',
                                            'checked' => $checked,
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                </div>
                            </div>

                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    PC쇼핑몰 판매상태
                                </label>
                                <div class="form-inline">
                                    <?php
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_PC_SELL_FLAG' ) == 'Y'){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_is_pc_sell',
                                            'id' => 'i_is_pc_sell_Y',
                                            'value' => 'Y',
                                            'label' => '판매함',
                                            'checked' => 'checked',
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                    <?php
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_PC_SELL_FLAG' ) == 'N'){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_is_pc_sell',
                                            'id' => 'i_is_pc_sell_N',
                                            'value' => 'N',
                                            'label' => '판매안함',
                                            'checked' => $checked,
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                </div>
                            </div>

                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    모바일쇼핑몰 노출상태
                                </label>
                                <div class="form-inline">
                                    <?php
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_MOBILE_OPEN_FLAG' ) == 'Y'){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_is_mobile_open',
                                            'id' => 'i_is_mobile_open_Y',
                                            'value' => 'Y',
                                            'label' => '노출함',
                                            'checked' => $checked,
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                    <?php
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_MOBILE_OPEN_FLAG' ) == 'N'){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_is_mobile_open',
                                            'id' => 'i_is_mobile_open_N',
                                            'value' => 'N',
                                            'label' => '노출안함',
                                            'checked' => $checked,
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                </div>
                            </div>

                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    모바일쇼핑몰 판매상태
                                </label>
                                <div class="form-inline">
                                    <?php
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_MOBILE_SELL_FLAG' ) == 'Y'){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_is_mobile_sell',
                                            'id' => 'i_is_mobile_sel_Y',
                                            'value' => 'Y',
                                            'label' => '판매함',
                                            'checked' => $checked,
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                    <?php
                                        $checked = false;
                                        if( _elm( $aDatas, 'G_MOBILE_SELL_FLAG' ) == 'N'){
                                            $checked = true;
                                        }
                                        $setParam = [
                                            'name' => 'i_is_mobile_sell',
                                            'id' => 'i_is_mobile_sell_N',
                                            'value' => 'N',
                                            'label' => '판매안함',
                                            'checked' => $checked,
                                            'extraAttributes' => [
                                            ]
                                        ];
                                        echo getRadioButton($setParam);
                                    ?>
                                </div>
                            </div>

                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    원산지
                                </label>
                                <div class="form-inline">
                                    <input type="text" class="form-control mt-2" name="i_origin_name" value="<?php echo _elm( $aDatas, 'G_ORIGIN_NAME' )?>"
                                    placeholder="원산지를 입력해주세요." />
                                </div>
                            </div>
                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    제조사
                                </label>
                                <div class="form-inline">
                                    <input type="text" class="form-control mt-2" name="i_maker_name" value="<?php echo _elm( $aDatas, 'G_MAKER_NAME' )?>"
                                    placeholder="원산지를 입력해주세요." />
                                </div>
                            </div>
                            <div class="input-group" style="margin-bottom:16px">
                                <label class="label body2-c ">
                                    브랜드
                                </label>
                                <div class="form-inline">
                                <?php
                                    echo getButton([
                                        'text' => '브랜드 선택',
                                        'class' => 'btn',
                                        'style' => 'width: 180px; height: 46px',
                                        'extra' => [
                                            'onclick' => 'event.preventDefault();dropDownLayer(\'brand\')',
                                        ]
                                    ]);
                                ?>
                                </div>
                                <div id="dropdown-layer-brand" class="dropdown-layer" style="display:none">
                                </div>
                            </div>
                            <div class="input-group">
                                <div id="select-brand">
                                    <?php
                                    if( empty( _elm( $aDatas, 'G_BRAND_IDX' ) ) === false ){

                                    ?>
                                    <div class="dropdown-list-item" data-id="brand_<?php echo _elm( $aDatas, 'G_BRAND_IDX' )?>">
                                        <div class="dropdown-content">
                                            <div class="left-content">
                                                <?php
                                                    $setParam = [
                                                        'name' => 'i_is_brand_main',
                                                        'id' => 'i_is_brand_mainbrand_'._elm( $aDatas, 'G_BRAND_IDX' ),
                                                        'value' => _elm( $aDatas, 'G_BRAND_IDX' ),
                                                        'label' => '',
                                                        'checked' => 'checked',
                                                        'extraAttributes' => [
                                                        ]
                                                    ];
                                                    echo getRadioButton($setParam);
                                                ?>
                                            </div>
                                            <div class="center-content">
                                                <div class="text"><?php echo _elm( $aDatas, 'G_BRAND_NAME' )?></div>
                                            </div>
                                            <div class="right-content">
                                                <svg class="tabler-icon-circle-x" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" fill="white"/>
                                                    <path d="M8 8L12 12L8 8ZM12 8L8 12L12 8Z" fill="white"/>
                                                    <path d="M8 8L12 12M12 8L8 12M1 10C1 11.1819 1.23279 12.3522 1.68508 13.4442C2.13738 14.5361 2.80031 15.5282 3.63604 16.364C4.47177 17.1997 5.46392 17.8626 6.55585 18.3149C7.64778 18.7672 8.8181 19 10 19C11.1819 19 12.3522 18.7672 13.4442 18.3149C14.5361 17.8626 15.5282 17.1997 16.364 16.364C17.1997 15.5282 17.8626 14.5361 18.3149 13.4442C18.7672 12.3522 19 11.1819 19 10C19 8.8181 18.7672 7.64778 18.3149 6.55585C17.8626 5.46392 17.1997 4.47177 16.364 3.63604C15.5282 2.80031 14.5361 2.13738 13.4442 1.68508C12.3522 1.23279 11.1819 1 10 1C8.8181 1 7.64778 1.23279 6.55585 1.68508C5.46392 2.13738 4.47177 2.80031 3.63604 3.63604C2.80031 4.47177 2.13738 5.46392 1.68508 6.55585C1.23279 7.64778 1 8.8181 1 10Z" stroke="#616876" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <?php

                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 묶음 종료 -->
                <!-- 묶음 시작 -->
                <div style="padding-bottom:1.1rem">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        SEO (검색엔진 최적화)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="input-group">
                                <label class="label body2-c">
                                    제목
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control mt-2" name="i_seo_title" placeholder="메타테그 제목" value="<?php echo _elm( $aDatas, 'G_SEO_TITLE' )?>" />
                            </div>
                            <div class="input-group">
                                <label class="label body2-c">
                                    설명
                                    <span>*</span>
                                </label>
                                <input type="text" class="form-control mt-2" name="i_seo_description" placeholder="메타태그 설명" value="<?php echo _elm( $aDatas, 'G_SEO_DESCRIPTION' )?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 묶음 종료 -->



                <!-- 묶음 시작 -->
                <div style="padding-bottom:1.1rem">
                    <div class="card">
                        <!-- 카드 타이틀 -->
                        <div class="accordion-card"
                            style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                        fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                    </svg>
                                    <p class="body1-c ms-2 mt-1">
                                        기타 설정
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="input-group">
                                <label class="label body2-c">
                                    최소 구매 수량
                                </label>
                            </div>
                            <div class="input-group" style="margin-top:-0.7rem;">
                                <input type="text" name="i_min_buy_count" id="i_min_buy_count" class="form-control" value="<?php echo _elm( $aDatas, 'G_MIN_BUY_COUNT' )?>" placeholder="숫자만 입력">
                            </div>
                            <div class="input-group">
                                <label class="label body2-c"  style="width:100%" >
                                    1인 구매시 최대 수량
                                </label>
                            </div>
                            <div class="input-group" style="margin-top:-0.7rem;">
                                <input type="text" class="form-control" name="i_mem_max_buy_count" id="i_mem_max_buy_count" value="<?php echo _elm( $aDatas, 'G_MEM_MAX_BUY_COUNT' )?>" placeholder="숫자만 입력">
                            </div>

                            <div class="input-group">
                                <?php
                                    $checked = false;
                                    if( _elm( $aDatas, 'G_IS_ADULT_PRODUCT' ) == 'Y' ){
                                        $checked = true;
                                    }
                                    $setParam = [
                                        'name' => 'i_is_adult_product',
                                        'id' => 'i_is_product_type_Y',
                                        'value' => 'Y',
                                        'label' => '미성년자 구매 불가능',
                                        'checked' => $checked,
                                        'extraAttributes' => [
                                        ]
                                    ];
                                    echo getCheckBox($setParam);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 묶음 종료 -->
            </div>
        </div>
    </div>
    <div style="text-align: center; margin-top: 52px">
        <?php
        echo getButton([
            'text' => '닫기',
            'class' => 'btn',
            'style' => 'width: 180px; height: 46px',
            'extra' => [
                'onclick' => 'event.preventDefault();$(".btn-close").trigger("click")',
            ]
        ]);
        ?>

        <?php
        echo getIconButton([
            'txt' => '저장',
            'icon' => 'success',
            'buttonClass' => 'btn btn-success',
            'buttonStyle' => 'width:180px; height: 46px',
            'width' => '21',
            'height' => '20',
            'stroke' => 'white',
            'extra' => [
                'type' => 'button',
                'onclick' => 'frmModifyConfirm(event);',
            ]
        ]);
        ?>
    </div>


</div>
<input type="file" name="i_goods_img[]" id="i_goods_img" multiple style="display: none;">

<?php echo form_close() ?>
<!-- Modal S-->
<div class="modal fade" id="dataModal" tabindex="-1" style="margin-top:3em;" aria-labelledby="dataodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:80vh">

        </div>
    </div>
</div>
<!-- Modal E-->
<!-- info Modal(사이즈 때문에 나눔) S-->
<div class="modal fade" id="infoModal" tabindex="-1" style="margin-top:3em;" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="max-height:90vh;display:flex;flex-direction: column;width:70vh">

        </div>
    </div>
</div>
<!-- Modal E-->


<script>


</script>




<?php

// $owensView->setFooterJs('/assets/js/goods/goods/tooltip.js');




$script = "
";

$owensView->setFooterScript($script);