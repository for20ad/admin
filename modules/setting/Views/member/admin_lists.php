<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }
    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];

    $aConfig          = _elm($pageDatas, 'aConfig', []);

    $aMemberLists    = _elm($pageDatas, 'member_lists', []);
    $aMemberGroup    = _elm($pageDatas, 'member_group', []);

    $aGetData        = _elm( $pageDatas, 'getData', [] );

?>

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
                                관리자 검색
                            </p>
                        </div>
                        <!-- 아코디언 토글 버튼 -->
                        <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
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

                    <?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_search', 'onSubmit' => 'return false;', 'autocomplete' => 'off']); ?>
                    <input type="hidden" name="page" id="page" value="<?php echo _elm( $aGetData, 'page' );?>">
                    <input type="hidden" name="getData" id="getData" value="<?php echo _elm( $aGetData, 'page' );?>">
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
                                    <th class="no-border-bottom">상태</th>
                                    <td class="no-border-bottom">
                                        <?php
                                            $options  = _elm($aConfig, 'status', []);
                                            $extras   = ['id' => 's_status', 'class' => 'form-select', 'style' => 'max-width: 174px'];
                                            $selected = _elm( $aGetData, 's_status' );
                                            echo getSelectBox('s_status', $options, $selected, $extras);
                                        ?>
                                    </td>
                                    <th class="no-border-bottom">
                                        그룹
                                    </th>
                                    <td class="no-border-bottom">
                                    <?php
                                        $options = [''=>'전체'];
                                        $options+= $aMemberGroup??[];
                                        $extras   = ['id' => 's_group', 'class' => 'form-select', 'style' => 'max-width: 174px'];
                                        $selected = _elm( $aGetData, 's_group' );
                                        echo getSelectBox('s_group', $options, $selected, $extras);
                                    ?>
                                    </td>
                                </tr>

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
                                </tbody>
                            </table>
                        </div>
                        <?php echo form_close();?>
                        <div style="text-align: center; margin-top: 52px">
                        <?php
                        echo getIconButton([
                            'txt' => '검색',
                            'icon' => 'search',
                            'buttonClass' => 'btn btn-success',
                            'buttonStyle' => 'width: 180px; height: 46px',
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
                                'onclick' => 'location.href="'._link_url('/setting/memberLists').'"',
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
                        공지사항
                    </p>
                </div>
                <!-- 아코디언 토글 버튼 -->
                <label class="form-selectgroup-item" onclick="toggleForm( $(this) )">
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
            <!-- 테이블 -->
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
                                <th>휴대폰번호</th>
                                <th>상태</th>
                                <th>등록일</th>
                                <th>최종접속일</th>
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
    </div>
</div>

<?php
$owensView->setFooterJs('/assets/js/setting/member/lists.js');
