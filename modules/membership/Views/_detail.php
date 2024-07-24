<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();


    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aMemeberGrade    = _elm($view_datas, 'member_grades', []);
    $aData            = _elm($view_datas, 'aData', [] );

?>


<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_modify', 'autocomplete' => 'off']); ?>
<input type="hidden" name="i_mb_idx" value="<?php echo _elm( $aData, 'MB_IDX' )?>">
<div class="row row-deck row-cards">
    <!-- 카드1 -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="input-group required">
                    <label class="label body2-c">
                        아이디
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" name="i_user_id" id="i_user_id"
                        placeholder="아이디를 입력해주세요." onblur="dupCheckId( $(this) );" value="<?php echo _elm( $aData, 'MB_USERID' )?>" readonly/>
                </div>

                <div class="input-group required">
                    <label class="label body2-c">
                        비밀번호 변경
                        <span>*</span>
                    </label>
                    <?php
                    echo getButton([
                        'text' => '변경하기',
                        'class' => 'btn',
                        'style' => 'width: 81px;',
                        'extra' => [
                            'onclick' => 'showPasswordGroup()',
                            'id' => 'passChangeBtn',
                        ]
                    ]);
                    ?>

                </div>
                <div id="passGroup" class="formHide">
                    <div class="input-group required">
                        <label class="label body2-c">
                            비밀번호
                            <span>*</span>
                        </label>
                        <input type="password" class="form-control" name="i_password" id="i_password" data-password='true' />
                    </div>

                    <div class="input-group required">
                        <label class="label body2-c">
                            비밀번호 확인
                            <span>*</span>
                        </label>
                        <input type="password" class="form-control" name="i_password_check" id="i_password_check"  data-password='true' onblur="sameValueCheck( 'i_password', $(this) )"/>
                    </div>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        이름
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" placeholder="홍길동" name="i_user_name" id="i_user_name"  data-max-length="20" /
                        style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value="<?php echo _elm( $aData, 'MB_NM' )?>"/>
                    <span class="wordCount input-group-text"
                        style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        <?php echo mb_strlen( _elm( $aData, 'MB_NM' ), 'UTF-8' )?>/20
                    </span>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        휴대폰번호
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" name="i_mobile_num" id="i_mobile_num" data-mobile='true' value="<?php echo _elm( $aData, 'MB_MOBILE_NUM_DEC' )?>" />
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        회원등급
                        <span>*</span>
                    </label>
                    <?php
                        $options  = $aMemeberGrade?? [];
                        $extras   = ['id' => 'i_grade', 'class' => 'form-select', 'style' => 'max-width: 174px'];
                        $selected = _elm( $aData, 'MB_GRADE_IDX' );
                        echo form_dropdown('i_grade', $options, $selected, $extras);
                    ?>
                </div>
                <div class="input-group">
                    <label class="label body2-c">
                        회원상태
                        <span>*</span>
                    </label>
                    <?php
                        $options  = _elm($aConfig, 'status', []);
                        $extras   = ['id' => 'i_status', 'class' => 'form-select', 'style' => 'max-width: 174px'];
                        $selected = _elm( $aData, 'MB_STATUS' );
                        echo form_dropdown('i_status', $options, $selected, $extras);
                    ?>
                </div>

            </div>
        </div>
    </div>

    <div class="col-12" id="compMember">
        <div class="card">
            <!-- 카드 타이틀 -->
            <div class="card accordion-card"
                style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                            viewBox="0 0 4 4" fill="none">
                            <circle cx="2" cy="2" r="2" fill="#206BC4" />
                        </svg>
                        <p class="body1-c ms-2 mt-1">
                            기업정보
                        </p>
                    </div>
                    <!-- 아코디언 토글 버튼 -->
                    <label class="form-selectgroup-item"  onclick="toggleForm( $(this) )">
                        <input type="radio" name="icons" value="home"
                            class="form-selectgroup-input" checked />
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

            <div class="card-body" >
                <div class="input-group">
                    <label class="label body2-c">
                        회사명
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" name="i_mb_com_name" id="I_MB_COM_NAME" value="<?php echo _elm( $aData, 'MB_COM_NAME' )?>"  placeholder="주소검색시 자동입력"/>
                </div>
                <div class="input-group">
                    <label class="label body2-c">
                        대표자
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" name="i_mb_com_ceo" id="i_mb_com_ceo" value="<?php echo _elm( $aData, 'MB_COM_CEO' )?>"/>
                </div>

                <div class="input-group">
                    <label class="label body2-c">
                        사업자등록번호
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" name="i_mb_business_number" id="i_mb_business_number" data-business-number='true' value="<?php echo _add_dash_biz_num( _elm( $aData, 'MB_BUSINESS_NUMBER' ) )?>"/>

                </div>

                <div class="input-group">
                    <label class="label body2-c">
                        업태
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" name="i_mb_com_sevice" id="i_mb_com_sevice" value="<?php echo _elm( $aData, 'MB_COM_SEVICE' )?>"/>
                </div>

                <div class="input-group">
                    <label class="label body2-c">
                        종목
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" name="i_mb_comp_item" id="i_mb_comp_item" value="<?php echo _elm( $aData, 'MB_COMP_ITEM' )?>" />
                </div>

                <div class="input-group">
                    <label class="label body2-c">
                        주소
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" style="width:1.8rem" name="i_mb_com_zipcd" id="i_mb_com_zipcd" value="<?php echo _elm( $aData, 'MB_COM_ZIPCD' )?>" onclick="execDaumPostcode('i_mb_com_zipcd', 'i_mb_com_addr')" readonly />&nbsp;&nbsp;
                    <input type="text" class="form-control" name="i_mb_com_addr" id="i_mb_com_addr" value="<?php echo _elm( $aData, 'MB_COM_ADDR' )?>"  placeholder="주소검색시 자동입력" onclick="execDaumPostcode('i_mb_com_zipcd', 'i_mb_com_addr')" readonly/>
                </div>
                <div class="input-group">
                    <label class="label body2-c">
                    </label>
                    <input type="text" class="form-control" name="i_mb_com_addr_sub" id="i_mb_com_addr_sub" value="<?php echo _elm( $aData, 'MB_COM_ADDR_SUB' )?>" placeholder="상세주소 입력"/>
                </div>




            </div>
        </div>
    </div>

    <!-- 카드2 -->
    <div class="col-12">
        <div class="card">
            <!-- 카드 타이틀 -->
            <div class="card accordion-card"
                style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                            viewBox="0 0 4 4" fill="none">
                            <circle cx="2" cy="2" r="2" fill="#206BC4" />
                        </svg>
                        <p class="body1-c ms-2 mt-1">
                            부가정보
                        </p>
                    </div>
                    <!-- 아코디언 토글 버튼 -->
                    <label class="form-selectgroup-item"  onclick="toggleForm( $(this) )">
                        <input type="radio" name="icons" value="home"
                            class="form-selectgroup-input" checked />
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
                <div class="input-group">
                    <label class="label body2-c">
                        기본배송지
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" style="width:1.8rem" name="i_zip_cd" id="i_zip_cd" value="<?php echo _elm( $aData, 'D_ZIP_CD' )?>" onclick="execDaumPostcode('i_zip_cd', 'i_addr')" readonly />&nbsp;&nbsp;
                    <input type="text" class="form-control" name="i_addr" id="i_addr" value="<?php echo _elm( $aData, 'D_ADDR' )?>"  placeholder="주소검색시 자동입력" onclick="execDaumPostcode('i_zip_cd', 'i_addr')" readonly/>
                </div>
                <div class="input-group">
                    <label class="label body2-c">
                    </label>
                    <input type="text" class="form-control" name="i_addr_sub" id="i_addr_sub" value="<?php echo _elm( $aData, 'D_ADDR_SUB' )?>" placeholder="상세주소 입력"/>
                </div>

                <div class="input-group">
                    <label class="label body2-c">
                        생년월일
                        <span>*</span>
                    </label>
                    <div class="col-2">
                        <input type="text" class="form-control" style="width:120px;" name="i_mb_birth" id="i_mb_birth" value="<?php echo _elm( $aData, 'MB_BIRTH' )?>"/>
                    </div>
                </div>

                <div class="input-group">
                    <label class="label body2-c">
                        성별
                        <span>*</span>
                    </label>
                    <?php
                        $checked = false;
                        if('w' == _elm( $aData, 'MB_GENDER' ) ){
                            $checked = true;
                        }
                        $setParam = [
                            'name' => 'i_mb_gender',
                            'id' => 'i_mb_gender_w',
                            'value' => 'w',
                            'label' => '여성',
                            'checked' => $checked,
                            'extraAttributes' => [
                            ]
                        ];
                        echo getRadioButton($setParam);
                    ?>
                    <?php
                        $checked = false;
                        if( 'm' == _elm( $aData, 'MB_GENDER' ) ){
                            $checked = true;
                        }
                        $setParam = [
                            'name' => 'i_mb_gender',
                            'id' => 'i_mb_gender_m',
                            'value' => 'm',
                            'label' => '님성',
                            'checked' => $checked,
                            'extraAttributes' => [
                            ]
                        ];
                        echo getRadioButton($setParam);
                    ?>

                </div>

                <div class="input-group">
                    <label class="label body2-c">
                    신체정보
                        <span>*</span>
                    </label>

                    <span style="width:4.8rem;padding-right:1.2rem">신장</span>
                    <input type="text" class="form-control" name="i_mb_height" id="i_mb_height" style="max-width:3.8rem" value="<?php echo _elm( $aData, 'MB_HEIGHT' )?>"/>
                    <span class="wordCount input-group-text"
                        style="border-top-left-radius:0px; border-bottom-left-radius: 0px;width:3.2rem;text-align:center">
                        cm
                    </span>
                </div>
                <div class="input-group">
                    <label class="label body2-c">
                    </label>
                    <span style="width:4.8rem;padding-right:1.2rem">몸무게</span>
                    <input type="text" class="form-control" name="i_mb_weight" id="i_mb_weight" style="max-width:3.8rem" value="<?php echo _elm( $aData, 'MB_WEIGHT' )?>" />
                    <span class="wordCount input-group-text"
                        style="border-top-left-radius:0px; border-bottom-left-radius: 0px;width:3.2rem;text-align:center">
                        kg
                    </span>
                </div>
                <div class="input-group">
                    <label class="label body2-c">
                    </label>
                    <span style="width:4.8rem;padding-right:1.2rem">발사이즈</span>
                    <input type="text" class="form-control" name="i_mb_foot_size" id="i_mb_foot_size" style="max-width:3.8rem" value="<?php echo _elm( $aData, 'MB_FOOT_SIZE' )?>" />
                    <span class="wordCount input-group-text"
                        style="border-top-left-radius:0px; border-bottom-left-radius: 0px;width:3.2rem;text-align:center">
                        mm
                    </span>
                </div>
                <div class="input-group">
                    <label class="label body2-c">
                    </label>
                    <span style="width:4.8rem;padding-right:1.2rem">허리둘레</span>
                    <input type="text" class="form-control" name="i_mb_waist" id="i_mb_waist" style="max-width:3.8rem" value="<?php echo _elm( $aData, 'MB_WAIST' )?>" />
                    <span class="wordCount input-group-text"
                        style="border-top-left-radius:0px; border-bottom-left-radius: 0px;width:3.2rem;text-align:center">
                        in
                    </span>
                </div>


                <div class="input-group">
                    <label class="label body2-c">
                        관리자 메모
                        <span>*</span>
                    </label>
                    <textarea name="i_mb_adm_memo" class="form-control"><?php echo htmlspecialchars_decode( _elm( $aData, 'MB_ADM_MEMO' ) )?></textarea>
                </div>
                <div class="input-group-bottom-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 20 20" fill="none">
                        <path
                            d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z"
                            fill="#616876" />
                        <path d="M10 6.66667H10.0083" stroke="white" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9.1665 10H9.99984V13.3333H10.8332" stroke="white"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    관리자가 등록한 메모입니다.
                    <a style="color: #1D273B">
                        회원에 대해 필요한 메모
                    </a>
                    입력 가능합니다
                </div>

            </div>
        </div>
    </div>

    <!-- 버튼 -->
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
        if( _elm( $aData, 'MB_STATUS' ) != '9' ){
            echo getButton([
                'text' => '삭제',
                'class' => 'btn btn-secondary',
                'style' => 'width: 180px; height: 46px',
                'extra' => [
                    'onclick' => 'deleteMember()',
                ]
            ]);
        }
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
                    'onclick' => 'modfiyConfirm();',
                ]
            ]);
        ?>


    </div>
</div>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<?php echo getPostSearch() ?>

<script>

$(document).ready(function() {
    let compMember= "<?php echo _elm( $aData, 'MB_COM_NAME' )?>";
    console.log( compMember );
    if (compMember == '') {
        setTimeout(function(){
            $("#compMember .form-selectgroup-item").trigger('click');
        }, 200);
    }

});

let idChk = true;
let passwdChk = true;
let orgUseID = '<?php echo _elm( $aData, 'MB_USERID' )?>';

</script>
<?php echo form_close() ?>