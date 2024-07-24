<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }

?>
<div class="d-flex">
    <div class="dashboard">
        <div class="dashboard-container">
            <div class="page-body">
                <div class="page page-center">
                    <div class="container container-tight py-4">
                        <div class="text-center mb-3">
                            <div class="navbar-brand navbar-brand-autodark">
                                <!-- <img src="../../dist/img/logo.png" width="110" alt="하이오너" class="navbar-brand-image"> -->
                            </div>
                        </div>
                        <div class="card card-md">
                            <div class="card-body text-center py-4 p-sm-4">
                                <h2>본 사이트는 산수유람<br /> 관리자 페이지 입니다</h2>
                                <p class="text-muted">비승인된 사람이나 비정상적인 접근으로 로그인할 경우<br />
                                    민ㆍ형사상 책임을 질 수 있습니다</p>
                            </div>
                            <div class="hr-text hr-text-center hr-text-spaceless">LOGIN</div>


                            <div class="card-body" id="loginFrm">
                                <div class="lg_step mb-4">
                                    <ul class="clearfix">
                                        <li class="on">01. 아이디/비밀번호 입력</li>
                                        <li>02. 2차 인증</li>
                                    </ul>
                                </div>
                                <?php
                                    $hidden = [];
                                    // $hidden['orderby'] =  _elm( $get , 'orderby' , 0);
                                    // $hidden['per_page'] = _elm( $get , 'per_page' , 0 );
                                    echo form_open('/member', ['method' => 'get', 'id'=>'frm_login', 'name' => 'frm_login', 'autocomplete' => 'off'] , $hidden);
                                ?>
                                <div class="mb-3">
                                    <label class="form-label">아이디</label>
                                    <input type="text" name="i_member_id" class="form-control ps-1">
                                </div>
                                <div>
                                    <label class="form-label">비밀번호</label>
                                    <input type="password" name="i_member_password" class="form-control ps-1">
                                </div>
                                <?php echo form_close()?>
                                <div class="mt-4">
                                    <a href="#" id="loginButton" class="btn btn-primary w-100" <?php echo csp_script_nonce()?>>
                                        로그인
                                    </a>
                                </div>
                                <div class="d-flex mt-3 justify-content-center">
                                    <a href="/adMember/find_info" class="link">아이디 · 비밀번호 찾기</a>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/login/login.js"></script>