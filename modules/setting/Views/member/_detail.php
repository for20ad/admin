<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();


    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);
    $aMemeberGroup    = _elm($view_datas, 'member_group', []);
    $aData            = _elm($view_datas, 'aData', [] );

?>
<style>
    .formHide{
        display:none;
    }
</style>
<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_modify', 'onSubmit' => 'return false;' ,'autocomplete' => 'off']); ?>
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
                        placeholder="아이디를 입력해주세요." onblur="dupCheckId( $(this) );" value="<?php echo _elm( $aData, 'MB_USERID' )?>"/>
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
                            'type' => 'button',
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
                    <input type="text" class="form-control" placeholder="이름을 입력해주세요" name="i_user_name" id="i_user_name"  data-max-length="20" /
                        style="border-top-right-radius:0px; border-bottom-right-radius: 0px" value="<?php echo _elm( $aData, 'MB_USERNAME' )?>"/>
                    <span class="wordCount input-group-text"
                        style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        <?php echo mb_strlen( _elm( $aData, 'MB_USERNAME' ) )?>/20
                    </span>
                </div>

                <div class="input-group required">
                    <label class="label body2-c">
                        휴대폰번호
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" name="i_mobile_num" id="i_mobile_num" data-mobile='true' value="<?php echo _elm( $aData, 'MB_MOBILE_NUM_DEC' )?>" />
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
                            기본정보
                        </p>
                    </div>
                    <!-- 아코디언 토글 버튼 -->
                    <label class="form-selectgroup-item"  onclick="toggleForm( $(this) )">
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
                        전화번호
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" name="i_tel_num" id="i_tel_num" value="<?php echo _elm( $aData, 'MB_TEL_NUM_DEC' )?>"/>
                </div>

                <div class="input-group">
                    <label class="label body2-c">
                        이메일주소
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" name="i_email" id="i_email" data-email='true' value="<?php echo _elm( $aData, 'MB_EMAIL_DEC' )?>"/>

                </div>

                <div class="input-group">
                    <label class="label body2-c">
                        부서
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" name="i_department" id="i_department" value="<?php echo _elm( $aData, 'MB_DEPARTMENT' )?>"/>
                </div>

                <div class="input-group">
                    <label class="label body2-c">
                        직급
                        <span>*</span>
                    </label>
                    <input type="text" class="form-control" name="i_position" id="i_position" value="<?php echo _elm( $aData, 'MB_POSITION' )?>" />
                </div>

                <div class="input-group">
                    <label class="label body2-c">
                        로그인제한
                        <span>*</span>
                    </label>
                    <?php
                        $options  = _elm($aConfig, 'status', []);
                        $extras   = ['id' => 'i_status', 'class' => 'form-select', 'style' => 'max-width: 174px'];
                        $selected = _elm( $aData, 'MB_STATUS' );
                        echo form_dropdown('i_status', $options, $selected, $extras);
                    ?>
                </div>
                <div class="input-group-bottom-text" style="margin-bottom: 0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                        viewBox="0 0 6 6" fill="none">
                        <circle cx="3" cy="3" r="3" fill="#616876" />
                    </svg>
                    상태가”대기”, “정지”인 경우 관리자가 접속할 수 없습니다
                </div>
                <div class="input-group-bottom-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4"
                        viewBox="0 0 6 6" fill="none">
                        <circle cx="3" cy="3" r="3" fill="#616876" />
                    </svg>
                    로그인을 5회 연속으로 실패할 경우 자동으로 “정지”상태로 변경됩니다
                </div>

                <div class="input-group required" style="margin-bottom:0">
                    <label class="label body2-c ">
                        관리등급
                        <span>*</span>
                    </label>
                    <div>
                        <?php
                        foreach( $aMemeberGroup as $key => $val )
                        {
                            $checked = false;
                            if( $key == _elm( $aData, 'MB_LEVEL' ) ){
                                $checked = true;
                            }
                            $setParam = [
                                'name' => 'i_admin_group',
                                'id' => 'i_admin_group',
                                'value' => $key,
                                'label' => $val,
                                'checked' => $checked,
                                'extraAttributes' => []
                            ];

                            echo getRadioButton($setParam);
                        }
                        ?>
                    </div>
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
                    관리자 등급에 따른 세부적인 메뉴 접근권한은
                    <a style="color: #1D273B">
                        권한관리에서 확인 및 설정
                    </a>
                    이 가능합니다
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
                'type' => 'button',
            ]
        ]);
        ?>
        <?php
        echo getButton([
            'text' => '삭제',
            'class' => 'btn btn-secondary',
            'style' => 'width: 180px; height: 46px',
            'extra' => [
                'onclick' => 'deleteMember()',
                'type' => 'button',
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
                    'onclick' => 'modfiyConfirm();',
                ]
            ]);
        ?>


    </div>
</div>
<script>
    let idChk = true;
let passwdChk = true;
let orgUseID = '<?php echo _elm( $aData, 'MB_USERID' )?>';
</script>
<?php echo form_close() ?>