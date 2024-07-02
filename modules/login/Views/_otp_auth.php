<?php
    helper( 'form' );
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }

    $auth_num   = _elm($pageDatas, 'auth_num', '');
    $mobile_num = _elm($pageDatas, 'mobile_num' , '');
    $mb_idx     = _elm($pageDatas, 'mb_idx' , '');

?>

<div class="lg_step mb-4">
    <ul class="clearfix">
        <li>01. 아이디/비밀번호 입력</li>
        <li class="on">02. 2차 인증</li>
    </ul>
</div>

<small class="form-hint mb-2">
    등록된 휴대폰으로 인증번호가 전송되었습니다.
</small>
<button onclick="resendAuthNum();" class="btn btn-secondary w-100 mb-3" type="button">인증번호 재 발송</button>
<?php
    $hidden = [];
    $hidden['auth_num']      = $auth_num;
    $hidden['mobile_num']    = $mobile_num;
    $hidden['mb_idx']        = $mb_idx;


    echo form_open('', ['method' => 'get', 'id'=>'frm_loginProc', 'name' => 'frm_loginProc' ,'class'=>'card', 'autocomplete' => 'off'] , $hidden);
?>
<div class="mb-3">
    <label class="form-label">인증번호</label>
    <div class="input-group custom-search-form size_xl">
        <input type="text" id="i_auth_number" name="i_auth_number" class="form-control" required="" value="<?php echo  $auth_num?>"placeholder="인증번호를 입력해주세요.">
        <div class="input-group-append">
            <span class="input-group-text" id="countdown">2:00</span>
        </div>
    </div>
</div>
<?php
    echo form_close();
?>
<div class="mt-4">
    <a href="javascript:loginProc();" id="loginProcButton"  class="btn btn-primary w-100">
        로그인
    </a>
</div>
<script src="/assets/js/login/auth.js" <?php csp_script_nonce()?>></script>