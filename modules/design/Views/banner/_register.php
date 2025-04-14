<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();

    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aConfig          = _elm($view_datas, 'aConfig', []);


?>

<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_register', 'autocomplete' => 'off']); ?>
<div class="row row-deck row-cards">
     <!-- 카드1 -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="input-group required">
                    <label class="label body2-c">
                        제목
                        <span>*</span>
                    </label>

                    <input type="text" class="form-control" placeholder="제목을 입력하세요." name="i_title" id="i_title" value="" data-max-length="30" style="border-top-right-radius:0px; border-bottom-right-radius: 0px"/>
                    <span class="wordCount input-group-text" style="border-top-left-radius:0px; border-bottom-left-radius: 0px">
                        0/30
                    </span>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        노출분류
                        <span>*</span>
                    </label>
                    <?php
                        $options  = [''=>'전체'];
                        $options += _elm( $aConfig, 'viewGbn' );
                        $extras   = ['id' => 'i_view_gbn', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;', 'onChange'=>'changeLocation( this.value, \'#i_locate\' );$(this).val() != \'SHOP\' ? $(\'#banner_position img\').hide() : $(\'#banner_position img\').show(); '];
                        $selected = '';
                        echo getSelectBox('i_view_gbn', $options, $selected, $extras);
                    ?>
                </div>
                <div class="input-group required" style="position: relative;">
                    <label class="label body2-c">
                        노출위치
                        <span>*</span>
                    </label>
                    <?php

                        $options  = _elm( _elm( $aConfig, 'viewLoc' ), 'SHOP' );
                        $extras   = ['id' => 'i_locate', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;', 'onchange' => '$(\'#banner_position img\').attr( \'src\', \'/dist/img/\'+$(this).val()+\'.gif\' )'];
                        $selected = '';
                        echo getSelectBox('i_locate', $options, $selected, $extras);
                        $viewLocShop = _elm(_elm($aConfig, 'viewLoc'), 'SHOP'); // 배열인지 확인
                        $firstKey = is_array($viewLocShop) ? array_keys($viewLocShop)[0] : ''; // 첫 번째 키값 가져오기
                    ?>
                    <div id="banner_position" style="position: absolute; left: calc(48% + 10px); top: 40%; transform: translateY(-50%);border:1px solid rgba(0, 0, 0, 0.15); margin:40px; padding:9px; border-radius:5px;">
                        <img src="/dist/img/<?php echo $firstKey?>.gif" alt="노출분류 아이콘" style="width: 280px; ">
                    </div>

                    <style>
                        .a {
                            color:##6C7A91;
                        }
                    </style>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        노출기간(시작)
                    </label>
                    <div class="input-icon">
                        <input type="text" class="form-control  datetimepicker"
                            name="i_period_start_date_time" id="i_period_start_date_time" readonly>
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
                <div class="input-group required">
                    <label class="label body2-c">
                        노출기간(종료)
                    </label>
                    <div class="input-icon">
                        <input type="text" class="form-control  datetimepicker"
                            name="i_period_end_date_time" id="i_period_end_date_time" readonly>
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
                <div class="input-group required">
                    <label class="label body2-c">
                        노출여부
                        <span>*</span>
                    </label>
                    <?php
                        $options  = _elm( $aConfig, 'status' );
                        $extras   = ['id' => 'i_status', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                        $selected = '';
                        echo getSelectBox('i_status', $options, $selected, $extras);
                    ?>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        이미지
                        <span>*</span>
                    </label>
                    <input type="file" class="form-control" name="i_img" id="i_img">
                </div>
                <div class="input-group">
                    <label class="label body2-c">
                        이동링크 주소
                    </label>
                    <input type="text" class="form-control" placeholder="이동링크 주소를 입력하세요." name="i_link_url" id="i_link_url" value=""/>
                </div>
                <div class="input-group required">
                    <label class="label body2-c">
                        오픈방식
                        <span>*</span>
                    </label>
                    <?php
                        $options  = _elm( $aConfig, 'openTarget' );
                        $extras   = ['id' => 'i_open_target', 'class' => 'form-select', 'style' => 'max-width: 150px;margin-right:0.235em;'];
                        $selected = '';
                        echo getSelectBox('i_open_target', $options, $selected, $extras);
                    ?>
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
                'onclick' => 'frmRegiserConfirm(event);',
            ]
        ]);
        ?>
    </div>
</div>

<?php echo form_close() ?>
<script>
flatpickr('.datetimepicker', {
    enableTime: true,
    dateFormat: 'Y-m-d H:i',
    time_24hr: true
});


// Initialize Toast UI Editor
var editor = new toastui.Editor({
    el: document.querySelector('#editor'),
    height: '300px',
    initialEditType: 'markdown',
    previewStyle: 'vertical',
    hooks: {
        addImageBlobHook: function (blob, callback) {
            var reader = new FileReader();
            reader.onload = function() {
                var base64Image = reader.result.split(',')[1];
                $.ajax({
                    url: '/apis/design/writeImage', // 파일을 업로드할 서버 측 엔드포인트
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ image: base64Image, path:'popup/editor' }),
                    success: function (response) {
                        // 서버에서 반환한 이미지 URL을 사용하여 에디터에 이미지를 추가
                        var imageUrl = response.url;
                        callback(imageUrl, 'alt text');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error uploading file:', textStatus, errorThrown);
                    }
                });
            };
            reader.readAsDataURL(blob);
        }
    }
});
</script>